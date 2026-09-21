<?php

namespace App\Models;

use Database\Factories\OperationalFunctionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $normalized_name
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name'])]
#[Hidden(['normalized_name'])]
class OperationalFunction extends Model
{
    /** @use HasFactory<OperationalFunctionFactory> */
    use HasFactory;

    /**
     * @return HasMany<User, $this>
     */
    public function karyawan(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function normalizeName(string $name): string
    {
        return Str::of($name)->squish()->lower()->toString();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
