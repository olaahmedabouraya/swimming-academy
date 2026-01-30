export interface Fee {
  id?: number;
  player_id: number;
  fee_type: 'registration' | 'renewal' | 'per_session' | 'withdrawal';
  amount: number;
  payment_date: string;
  payment_method: 'cash' | 'card' | 'bank_transfer';
  reference_number?: string;
  notes?: string;
  recorded_by: number;
  player?: any;
  recordedBy?: any;
  created_at?: string;
  updated_at?: string;
}

export interface FeeFilters {
  player_id?: number;
  fee_type?: string;
  start_date?: string;
  end_date?: string;
}



