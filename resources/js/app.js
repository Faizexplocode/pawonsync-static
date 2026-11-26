/**
 * Angka berjalan untuk elemen dengan atribut data-count-to.
 * Format rupiah dipakai jika data-count-format="rupiah".
 */
const formatAngka = (nilai, format) => {
    const bulat = Math.round(nilai).toLocaleString('id-ID');

    return format === 'rupiah' ? `Rp ${bulat}` : bulat;
};

const jalankanHitung = () => {
    const kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('[data-count-to]').forEach((elemen) => {
        const target = Number(elemen.dataset.countTo) || 0;
        const format = elemen.dataset.countFormat;

        if (kurangiGerak || target === 0) {
            elemen.textContent = formatAngka(target, format);

            return;
        }

        const durasi = 900;
        const mulai = performance.now();
        const langkah = (sekarang) => {
            const progres = Math.min(1, (sekarang - mulai) / durasi);
            const halus = 1 - Math.pow(1 - progres, 3);
            elemen.textContent = formatAngka(target * halus, format);

            if (progres < 1) {
                requestAnimationFrame(langkah);
            }
        };

        requestAnimationFrame(langkah);
    });
};

document.addEventListener('DOMContentLoaded', jalankanHitung);
