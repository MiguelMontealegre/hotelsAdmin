import { Payment } from "@models/payments/payment.model";

export interface Order {
  id: string;
  status: string;
  payment?: Payment;
  date: string;
  passengers?: Passenger[];
  createdAt: string;
  deletedAt ?: string;
}



export interface Passenger {
  name: string;
  birthdate: string;
  gender: string;
  email: string;
  phone: string;
  idType: string;
  id: string;
}

