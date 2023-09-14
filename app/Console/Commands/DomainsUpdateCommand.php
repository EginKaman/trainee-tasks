<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\DomainType;
use App\Models\{Country, Domain};
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\{DB, Http};

class DomainsUpdateCommand extends Command
{
    protected $signature = 'domains:update';

    protected $description = 'Update domains list';
    public function handle(): void
    {
        Country::chunk(1000, function (Collection $countries): void {
            $countries->each(function (Country $country): void {
                $this->info(now()->toDateTimeString() . ' ' . $country->iso_2_code);
                DB::beginTransaction();
                foreach (DomainType::cases() as $type) {
                    $this->setIsAvailableToFalse($country, $type);
                    $data = $this->getDomainsData($country, $type);

                    $dt = array_chunk($data, 2000);
                    foreach ($dt as $chunk) {
                        $chunk = array_map(function ($item) use ($country, $type) {
                            return [
                                'country_id' => $country->id,
                                'type' => $type,
                                'domain' => $item,
                                'is_available' => true,
                            ];
                        }, $chunk);

                        Domain::where('country_id', $country->id)->where('type', $type)
                            ->upsert($chunk, ['domain'], ['is_available']);
                    }
                }

                try {
                    DB::commit();
                } catch (\Throwable $th) {
                    $this->error('Error: ' . $th->getMessage() . ' in ' . $country->iso_2_code);
                }
            });
        });
    }

    private function setIsAvailableToFalse(Country $country, DomainType $type): void
    {
        Domain::where('country_id', $country->id)
            ->where('type', $type)
            ->where('is_available', true)
            ->update([
                'is_available' => false,
            ]);
    }

    private function getDomainsData(Country $country, DomainType $type): array
    {
        return Http::withUrlParameters([
            'endpoint' => 'https://api.nakarta.com/api/domainslist',
            'locale_code' => $country->iso_2_code,
        ])->get('{+endpoint}/{locale_code}', [
            'c_type' => $type->value,
        ])->json('domains');
    }
}
