import { User } from './user.interface';

export interface PlayerRating {
  id?: number;
  player_id: number;
  rated_by?: number;
  technique_score: number;
  endurance_score: number;
  speed_score: number;
  attitude_score: number;
  overall_score?: number;
  comments?: string;
  rating_date: string;
  player?: any; // Using any to avoid circular dependency
  ratedBy?: User;
}

export interface RatingFilters {
  player_id?: number;
}

