<?php

namespace Platform\Finance\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Platform\Finance\Models\FinanceAccount;
use Platform\Finance\Models\FinanceAccountType;

class ImportChartOfAccounts extends Command
{
    protected $signature = 'finance:import-chart-of-accounts 
                            {file : Path to CSV file}
                            {--team-id= : Team ID to import for}
                            {--dry-run : Show what would be imported without actually importing}';

    protected $description = 'Import chart of accounts from CSV file';

    public function handle()
    {
        $filePath = $this->argument('file');
        $teamId = $this->option('team-id') ?? auth()->user()?->currentTeam?->id;
        $dryRun = $this->option('dry-run');

        if (!$teamId) {
            $this->error('Team ID is required. Use --team-id option or ensure you are logged in with a current team.');
            return 1;
        }

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Importing chart of accounts for team ID: {$teamId}");
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No data will be imported');
        }

        $csvData = $this->parseCsvFile($filePath);
        
        if (empty($csvData)) {
            $this->error('No data found in CSV file');
            return 1;
        }

        $this->info("Found " . count($csvData) . " accounts to import");

        if ($dryRun) {
            $this->displayPreview($csvData);
            return 0;
        }

        return $this->importAccounts($csvData, $teamId);
    }

    private function parseCsvFile(string $filePath): array
    {
        $data = [];
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            return $data;
        }

        // Skip header row
        fgetcsv($handle, 0, ';');

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 4) continue;

            $numberFrom = str_replace(' ', '', trim($row[0]));
            $numberTo = trim($row[1]) ? str_replace(' ', '', trim($row[1])) : null;
            
            $data[] = [
                'number_from' => str_pad($numberFrom, 6, '0', STR_PAD_LEFT),
                'number_to' => $numberTo ? str_pad($numberTo, 6, '0', STR_PAD_LEFT) : null,
                'account_type' => trim($row[2]), // S/K/I
                'name' => trim($row[3]),
                'additional_type' => trim($row[4]) ?: null,
                'additional_function' => trim($row[5]) ?: null,
                'hf_type' => trim($row[6]) ?: null,
                'function' => trim($row[7]) ?: null,
                'fe' => trim($row[8]) ?: null,
                'factor_2' => trim($row[9]) ?: null,
                'account_1' => trim($row[10]) ?: null,
                'account_2' => trim($row[11]) ?: null,
                'additional_s_k_i' => trim($row[12]) ?: null,
                'asset_mirror_function' => trim($row[13]) ?: null,
                'purpose' => trim($row[14]) ?: null,
                's_b_i' => trim($row[15]) ?: null,
                'detail_eb_value' => trim($row[16]) ?: null,
            ];
        }

        fclose($handle);
        return $data;
    }

    private function displayPreview(array $data): void
    {
        $this->info("\nPreview of first 10 accounts:");
        $this->table(
            ['Number From', 'Number To', 'Type', 'Name'],
            array_slice($data, 0, 10)
        );
    }

    private function importAccounts(array $data, int $teamId): int
    {
        $this->info('Starting import...');
        
        DB::transaction(function () use ($data, $teamId) {
            $imported = 0;
            $skipped = 0;

            foreach ($data as $index => $accountData) {
                try {
                    // Determine account type based on S/K/I
                    $accountTypeCode = $this->determineAccountType($accountData['account_type']);
                    
                    // Find or create account type
                    $accountType = FinanceAccountType::firstOrCreate([
                        'team_id' => $teamId,
                        'code' => $accountTypeCode,
                    ], [
                        'name' => $this->getAccountTypeName($accountTypeCode),
                        'description' => "Imported account type: {$accountTypeCode}",
                        'is_active' => true,
                    ]);

                    // Create account
                    FinanceAccount::create([
                        'team_id' => $teamId,
                        'account_type_id' => $accountType->id,
                        'number' => $accountData['number_from'],
                        'number_from' => $accountData['number_from'],
                        'number_to' => $accountData['number_to'],
                        'name' => $accountData['name'],
                        'category' => $accountData['account_type'],
                        'function' => $accountData['function'],
                        'purpose' => $accountData['purpose'],
                        'external_ref' => $accountData['hf_type'],
                        'is_active' => true,
                    ]);

                    $imported++;
                    
                    if ($imported % 100 === 0) {
                        $this->info("Imported {$imported} accounts...");
                    }
                } catch (\Exception $e) {
                    $this->warn("Skipped account at row " . ($index + 2) . ": " . $e->getMessage());
                    $skipped++;
                }
            }

            $this->info("\nImport completed!");
            $this->info("Imported: {$imported} accounts");
            $this->info("Skipped: {$skipped} accounts");
        });

        return 0;
    }

    private function determineAccountType(string $type): string
    {
        return match($type) {
            'S' => 'asset',
            'K' => 'liability', 
            'I' => 'income',
            default => 'memo'
        };
    }

    private function getAccountTypeName(string $code): string
    {
        return match($code) {
            'asset' => 'Aktivkonto',
            'liability' => 'Passivkonto',
            'income' => 'Ertragskonto',
            'expense' => 'Aufwandskonto',
            default => 'Memo-Konto'
        };
    }
}
