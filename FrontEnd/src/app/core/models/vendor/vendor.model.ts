export interface Vendor {
  id: string;
  companyName: string;
  contactName: string;
  email: string;
  address: string;
  phone: string;
  productName: string;
  sellingPrice: number;
  wholesalePrice: number;
  minQuantity: number;
  productDescription: string;
  fileURL: string;
  createdAt: string;
  deletedAt?: string;
}
