import { Media } from "@models/media/media.model";
import { User } from "./user.model";

// import { User } from "@models/auth.models";
export interface UserWhosale {
    id: string;
    isApproved: number;
    companyName: string;
    companySize: string;
    phone: string;
    address: string;
    media: Media;
    active: number;
    createdAt: string;
    user: User;
}