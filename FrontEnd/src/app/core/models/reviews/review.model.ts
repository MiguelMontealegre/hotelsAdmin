import { User } from "@models/account/user.model";

export interface Review {
  id: string;
  title: string;
  content: string;
  valoration: number;
  pin?: boolean;
  user: User;
  createdAt: string;
  deletedAt?: string;
}

