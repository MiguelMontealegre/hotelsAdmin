import { Media } from "@models/media/media.model";
import { Product } from "@models/products/product.model";

export interface Hotel {
  id: string;
  name: string;
  description: string;
  country: string;
  city: string;
  address: string;
  pin: boolean;
  media?: Media[];
  products?: Product[];

  createdAt: string;
  deletedAt?: string;
}

