export interface Branch {
  id?: number;
  name: string;
  address: string;
  phone: string;
  email?: string;
  manager_name?: string;
  capacity: number;
  is_active: boolean;
  players_count?: number;
  schedules?: any[]; // Using any to avoid circular dependency
  monthly_records?: any[]; // Using any to avoid circular dependency
}

