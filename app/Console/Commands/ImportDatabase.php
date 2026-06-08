<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDatabase extends Command
{
    protected $signature = 'db:import {file=database/dump.sql : Path to SQL file}';
    protected $description = 'Import SQL file to database';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $this->info("Importing database from: $file");
        
        try {
            $sql = file_get_contents($file);
            
            // Parse SQL statements properly, handling comments and delimiters
            $statements = $this->parseSqlStatements($sql);

            $count = 0;
            foreach ($statements as $statement) {
                if (!empty(trim($statement))) {
                    try {
                        DB::unprepared($statement);
                        $count++;
                        $this->line("✓ Statement $count executed");
                    } catch (\Exception $e) {
                        $this->warn("⚠️  Statement $count failed: " . $e->getMessage());
                    }
                }
            }

            $this->info("✅ Database import completed! Total statements executed: $count");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error importing database: " . $e->getMessage());
            return 1;
        }
    }

    private function parseSqlStatements($sql)
    {
        $statements = [];
        $statement = '';
        $inString = false;
        $stringChar = null;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $nextChar = $i + 1 < $length ? $sql[$i + 1] : '';

            // Handle string literals
            if (($char === '"' || $char === "'") && ($i === 0 || $sql[$i - 1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                }
            }

            // Skip comments
            if (!$inString && $char === '-' && $nextChar === '-') {
                while ($i < $length && $sql[$i] !== '\n') {
                    $i++;
                }
                continue;
            }

            if (!$inString && $char === '/' && $nextChar === '*') {
                while ($i < $length - 1) {
                    if ($sql[$i] === '*' && $sql[$i + 1] === '/') {
                        $i += 2;
                        break;
                    }
                    $i++;
                }
                continue;
            }

            // Check for statement delimiter
            if (!$inString && $char === ';') {
                if (!empty(trim($statement))) {
                    $statements[] = trim($statement);
                }
                $statement = '';
                continue;
            }

            $statement .= $char;
        }

        // Add last statement if exists
        if (!empty(trim($statement))) {
            $statements[] = trim($statement);
        }

        return $statements;
    }
}
