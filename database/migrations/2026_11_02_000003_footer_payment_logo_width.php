<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Data-only migration: footer payment logos used a Tailwind class ("w-[115px]") for their size.
 * The dashboard form edits a plain number instead ("width" => 115), so convert the saved footer row.
 * (Tailwind only builds classes it finds in the code, so a class typed in the dashboard would not work.)
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->convert(function (array $payment) {
            if (preg_match('/w-\[(\d+)px\]/', $payment['class'] ?? '', $match)) {
                $payment['width'] = (int) $match[1];
            }
            unset($payment['class']);

            return $payment;
        });
    }

    public function down(): void
    {
        $this->convert(function (array $payment) {
            if (! empty($payment['width'])) {
                $payment['class'] = 'w-['.$payment['width'].'px]';
            }
            unset($payment['width']);

            return $payment;
        });
    }

    private function convert(callable $change): void
    {
        $row = DB::table('site_contents')->where('type', 'menu')->where('slug', 'footer')->first();
        $data = $row ? json_decode($row->data ?? '[]', true) : null;

        if (! is_array($data) || ! isset($data['payments'])) {
            return;
        }

        $data['payments'] = array_map($change, $data['payments']);

        DB::table('site_contents')->where('id', $row->id)->update(['data' => json_encode($data, JSON_THROW_ON_ERROR)]);
    }
};
