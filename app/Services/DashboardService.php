<?php

namespace App\Services;

use App\Models\Pengguna;
use App\Models\Anak;
use App\Models\Stunting;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    /**
     * Get all dashboard statistics for the main dashboard
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'total_stunting' => $this->getTotalStuntingCases(),
            'total_pengguna' => $this->getTotalUsers(),
            'total_anak' => $this->getTotalChildren(),
            'total_petugas' => $this->getTotalStaff()
        ];
    }
    
    /**
     * Get the total number of stunting cases
     *
     * @return int
     */
    public function getTotalStuntingCases(): int
    {
        try {
            return Stunting::where('status', 'Stunting')->count();
        } catch (Exception $e) {
            Log::error('Error counting stunting cases: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get the total number of users (parents)
     *
     * @return int
     */
    public function getTotalUsers(): int
    {
        try {
            return Pengguna::where('role', 'parent')->count();
        } catch (Exception $e) {
            Log::error('Error counting users: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get the total number of children
     *
     * @return int
     */
    public function getTotalChildren(): int
    {
        try {
            return Anak::count();
        } catch (Exception $e) {
            Log::error('Error counting children: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get the total number of staff
     *
     * @return int
     */
    public function getTotalStaff(): int
    {
        try {
            return Pengguna::where('role', 'admin')->count();
        } catch (Exception $e) {
            Log::error('Error counting staff: ' . $e->getMessage());
            return 0;
        }
    }
} 