import { Category } from "@models/categories/category.model";
import { Color } from "./product-color.model";
import { Feature } from "./product-feature.model";
import { Media } from "@models/media/media.model";
import { Size } from "./product-size.model";
import { Specification } from "./product-specification.model";
import { Tag } from "@models/tags/tag.model";
import { Hotel } from "@models/hotels/hotel.model";

export interface Product {
  id: string;
  title: string;
  price: number;
  discount?: number;
  description?: string;
  availableQuantity?: number;

  hotel: Hotel;


  userLike?: boolean;

  categories?: Category[];
  tags?: Tag[];
  media?: Media[];
  specifications?: Specification[];
  features?: Feature[];
  sizes?: Size[];
  colors?: Color[];
  likesCount?: number;
  comments?: Comment[];

  createdAt: string;
  ratings?: number;

  pin?: boolean;

  archivedAt?: string;
  deletedAt?: string;
}

