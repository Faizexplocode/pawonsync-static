<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Aktifkan Row Level Security di semua tabel schema public (khusus PostgreSQL / Supabase).
     *
     * Tanpa policy, akses lewat REST API Supabase (anon / publishable key) ditolak,
     * sedangkan Laravel tetap berjalan normal karena terhubung sebagai role postgres.
     */
    public function up(): void
    {
        foreach ($this->publicTables() as $table) {
            DB::statement(sprintf('alter table public.%s enable row level security', $this->quote($table)));
        }
    }

    public function down(): void
    {
        foreach ($this->publicTables() as $table) {
            DB::statement(sprintf('alter table public.%s disable row level security', $this->quote($table)));
        }
    }

    /**
     * @return array<int, string>
     */
    private function publicTables(): array
    {
        if (DB::getDriverName() !== 'pgsql') {
            return [];
        }

        return collect(DB::select("select tablename from pg_tables where schemaname = 'public'"))
            ->pluck('tablename')
            ->all();
    }

    private function quote(string $table): string
    {
        return '"'.str_replace('"', '""', $table).'"';
    }
};
