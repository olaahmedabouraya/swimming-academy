import { UserRole } from '../enums/user-role.enum';

export interface User {
  id: number;
  name: string;
  email: string;
  role: UserRole;
  phone?: string;
  date_of_birth?: string;
  address?: string;
  profile_image?: string;
}



