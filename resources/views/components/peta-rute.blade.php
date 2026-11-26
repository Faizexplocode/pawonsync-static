@props([
    'alamat',
    'lacak' => true,
    'lokasiUrl' => null,
    'awal' => null,
    'tinggi' => 'h-[320px]',
])

{{--
    Peta asli berbasis Leaflet + OpenStreetMap (tanpa API key).
    - Alamat venue diubah jadi koordinat lewat Nominatim.
    - Posisi kurir diambil dari GPS perangkat (watchPosition) secara realtime.
    - Rute dan estimasi waktu dihitung lewat OSRM.
    - Jika lokasiUrl diisi, posisi dikirim ke server setiap ~15 detik.
    Elemen mana pun di halaman dengan data-peta="jarak|eta|status" ikut diperbarui.
--}}

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .peta-kurir {
            position: relative;
        }

        .peta-kurir .leaflet-tile-pane {
            filter: saturate(0.7) brightness(1.04);
        }

        .peta-kurir .leaflet-control-attribution {
            font-size: 9px;
            background: rgba(255, 255, 255, 0.75);
        }

        .peta-kurir-titik {
            position: relative;
            width: 18px;
            height: 18px;
            border-radius: 9999px;
            background: #16874f;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(6, 48, 31, 0.35);
        }

        .peta-kurir-titik::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 9999px;
            background: rgba(22, 135, 79, 0.35);
            animation: peta-ripple 2s ease-out infinite;
        }

        @keyframes peta-ripple {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            100% {
                transform: scale(3);
                opacity: 0;
            }
        }

        .peta-venue-pin {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 9999px 9999px 9999px 0;
            transform: rotate(-45deg);
            background: #b7791f;
            border: 3px solid #fff;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
        }

        .peta-venue-pin span {
            transform: rotate(45deg);
            color: #fff;
            font: 700 12px/1 'Plus Jakarta Sans', sans-serif;
        }
    </style>
@endonce

<div {{ $attributes->merge(['class' => "peta-kurir isolate {$tinggi} overflow-hidden bg-mint-faint"]) }}
    data-peta-root
    data-alamat="{{ $alamat }}"
    data-lacak="{{ $lacak ? '1' : '0' }}"
    data-lokasi-url="{{ $lokasiUrl }}"
    data-csrf="{{ csrf_token() }}"
    data-awal="{{ $awal ? json_encode($awal) : '' }}">
    <div class="absolute inset-0 z-0" data-peta-kanvas></div>

    @if ($lacak)
        <button type="button" data-peta-pusat class="absolute bottom-11 right-4 z-[500] grid size-11 place-items-center rounded-full bg-white text-forest shadow-float" aria-label="Pusatkan ke posisi saya">
            <x-icon name="navigation" class="size-5" />
        </button>
    @endif

    {{ $slot }}
</div>

@once
    <script>
        (() => {
            const SURABAYA = [-7.2575, 112.7521];

            const ambilCache = (kunci) => {
                try {
                    return JSON.parse(localStorage.getItem(kunci) || 'null');
                } catch (e) {
                    return null;
                }
            };

            const simpanCache = (kunci, nilai) => {
                try {
                    localStorage.setItem(kunci, JSON.stringify(nilai));
                } catch (e) {
                    // penyimpanan tidak tersedia, abaikan
                }
            };

            const setTeks = (nama, teks) => {
                document.querySelectorAll(`[data-peta="${nama}"]`).forEach((el) => {
                    el.textContent = teks;
                });
            };

            const jarakMeter = (a, b) => {
                const r = 6371000;
                const rad = (d) => (d * Math.PI) / 180;
                const dLat = rad(b[0] - a[0]);
                const dLng = rad(b[1] - a[1]);
                const h = Math.sin(dLat / 2) ** 2 + Math.cos(rad(a[0])) * Math.cos(rad(b[0])) * Math.sin(dLng / 2) ** 2;

                return 2 * r * Math.asin(Math.sqrt(h));
            };

            const geocode = async (alamat) => {
                const kunci = 'pawonsync-geo:' + alamat;
                const cache = ambilCache(kunci);
                if (cache) {
                    return cache;
                }

                const cari = async (q) => {
                    const url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&q=' + encodeURIComponent(q);
                    const res = await fetch(url, { headers: { 'Accept-Language': 'id' } });
                    const data = await res.json();

                    return data[0] ? [Number(data[0].lat), Number(data[0].lon)] : null;
                };

                // Coba alamat lengkap, lalu bagian setelah koma pertama (lebih umum)
                let hasil = await cari(alamat);
                if (! hasil && alamat.includes(',')) {
                    hasil = await cari(alamat.split(',').slice(1).join(',').trim());
                }
                if (hasil) {
                    simpanCache(kunci, hasil);
                }

                return hasil;
            };

            const mulai = async (root) => {
                const map = L.map(root.querySelector('[data-peta-kanvas]'), {
                    zoomControl: false,
                    attributionControl: true,
                }).setView(SURABAYA, 12);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap',
                }).addTo(map);

                const ikonKurir = L.divIcon({ className: '', html: '<div class="peta-kurir-titik"></div>', iconSize: [18, 18], iconAnchor: [9, 9] });
                const ikonVenue = L.divIcon({ className: '', html: '<div class="peta-venue-pin"><span>V</span></div>', iconSize: [34, 34], iconAnchor: [17, 34] });

                let venue = null;
                let kurir = null;
                let markerKurir = null;
                let lingkaranAkurasi = null;
                let garisRute = null;
                let asalRuteTerakhir = null;
                let waktuRuteTerakhir = 0;
                let kirimTerakhir = { waktu: 0, titik: null };
                let sudahFit = false;

                const fitSemua = () => {
                    const titik = [venue, kurir].filter(Boolean);
                    if (titik.length === 2) {
                        map.fitBounds(L.latLngBounds(titik), { padding: [48, 48], maxZoom: 16 });
                    } else if (titik.length === 1) {
                        map.setView(titik[0], 15);
                    }
                };

                const hitungRute = async () => {
                    if (! venue || ! kurir) {
                        return;
                    }

                    const sekarang = Date.now();
                    const berpindah = asalRuteTerakhir ? jarakMeter(asalRuteTerakhir, kurir) : Infinity;
                    if (berpindah < 150 && sekarang - waktuRuteTerakhir < 60000) {
                        return;
                    }
                    asalRuteTerakhir = kurir;
                    waktuRuteTerakhir = sekarang;

                    try {
                        const url = `https://router.project-osrm.org/route/v1/driving/${kurir[1]},${kurir[0]};${venue[1]},${venue[0]}?overview=full&geometries=geojson`;
                        const data = await (await fetch(url)).json();
                        const rute = data.routes && data.routes[0];
                        if (! rute) {
                            return;
                        }

                        const koordinat = rute.geometry.coordinates.map(([lng, lat]) => [lat, lng]);
                        if (garisRute) {
                            garisRute.setLatLngs(koordinat);
                        } else {
                            garisRute = L.polyline(koordinat, { color: '#0b4a2e', weight: 5, opacity: 0.85, lineCap: 'round' }).addTo(map);
                        }

                        setTeks('jarak', (rute.distance / 1000).toFixed(1).replace('.', ',') + ' km');
                        setTeks('eta', Math.max(1, Math.round(rute.duration / 60)) + ' mnt');
                    } catch (e) {
                        const lurus = jarakMeter(kurir, venue) / 1000;
                        setTeks('jarak', '±' + lurus.toFixed(1).replace('.', ',') + ' km');
                    }
                };

                const kirimLokasi = async () => {
                    const url = root.dataset.lokasiUrl;
                    if (! url || ! kurir) {
                        return;
                    }

                    const sekarang = Date.now();
                    const berpindah = kirimTerakhir.titik ? jarakMeter(kirimTerakhir.titik, kurir) : Infinity;
                    if (sekarang - kirimTerakhir.waktu < 15000 && berpindah < 25) {
                        return;
                    }
                    kirimTerakhir = { waktu: sekarang, titik: kurir };

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': root.dataset.csrf,
                            },
                            body: JSON.stringify({ latitude: kurir[0], longitude: kurir[1] }),
                        });
                        if (res.ok) {
                            setTeks('kirim', 'Terkirim ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }));
                        }
                    } catch (e) {
                        setTeks('kirim', 'Gagal mengirim, mencoba lagi…');
                    }
                };

                const perbaruiKurir = (lat, lng, akurasi, dariGps = true) => {
                    kurir = [lat, lng];
                    if (markerKurir) {
                        markerKurir.setLatLng(kurir);
                        lingkaranAkurasi.setLatLng(kurir).setRadius(akurasi || 0);
                    } else {
                        lingkaranAkurasi = L.circle(kurir, { radius: akurasi || 0, color: '#16874f', weight: 0, fillOpacity: 0.1 }).addTo(map);
                        markerKurir = L.marker(kurir, { icon: ikonKurir, zIndexOffset: 1000 }).addTo(map);
                    }

                    if (! sudahFit) {
                        sudahFit = true;
                        fitSemua();
                    }

                    hitungRute();
                    if (dariGps) {
                        kirimLokasi();
                    }
                };

                const awal = root.dataset.awal ? JSON.parse(root.dataset.awal) : null;
                if (awal) {
                    perbaruiKurir(awal[0], awal[1], 0, false);
                    sudahFit = false;
                }

                geocode(root.dataset.alamat).then((titik) => {
                    if (! titik) {
                        setTeks('status', 'Alamat venue belum ditemukan di peta');

                        return;
                    }
                    venue = titik;
                    L.marker(venue, { icon: ikonVenue }).addTo(map).bindPopup(root.dataset.alamat);
                    fitSemua();
                    hitungRute();
                }).catch(() => setTeks('status', 'Gagal memuat lokasi venue'));

                root.querySelector('[data-peta-pusat]')?.addEventListener('click', () => {
                    if (kurir) {
                        map.setView(kurir, 16, { animate: true });
                    } else {
                        fitSemua();
                    }
                });

                if (root.dataset.lacak !== '1') {
                    return;
                }

                if (! window.isSecureContext) {
                    setTeks('status', 'GPS butuh HTTPS atau localhost');

                    return;
                }

                if (! navigator.geolocation) {
                    setTeks('status', 'Perangkat tidak mendukung GPS');

                    return;
                }

                setTeks('status', 'Mencari sinyal GPS…');
                navigator.geolocation.watchPosition(
                    (pos) => {
                        setTeks('status', 'GPS aktif · akurasi ' + Math.round(pos.coords.accuracy) + ' m');
                        perbaruiKurir(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
                    },
                    (err) => {
                        setTeks('status', err.code === 1 ? 'Izin lokasi ditolak' : 'Sinyal GPS belum didapat');
                    },
                    { enableHighAccuracy: true, maximumAge: 5000, timeout: 20000 },
                );
            };

            const jalankan = () => {
                if (! window.L) {
                    return;
                }
                document.querySelectorAll('[data-peta-root]').forEach((root) => mulai(root));
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', jalankan);
            } else {
                jalankan();
            }
        })();
    </script>
@endonce
