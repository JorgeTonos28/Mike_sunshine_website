export type Block = {
    type: "heading" | "paragraph" | "bullets" | "numbered" | "table";
    text?: string;
    items?: string[];
    rows?: string[][];
};
export type ArtworkImage = { id: number; path: string; alt: string };
export type StudioService = {
    slug: string;
    title: string;
    eyebrow: string;
    subject: string;
    intro: string;
    description: string;
    image: string;
    hero: string;
    cta: string;
    message: string;
    details: string[];
};
export type Artwork = {
    id: number;
    title: string;
    slug: string;
    kind: "painting" | "sculpture";
    category: string | null;
    summary: string | null;
    technique: string | null;
    dimensions: string | null;
    edition: string | null;
    year: number | null;
    price_minor: number | null;
    currency: string;
    availability: string;
    requestable: boolean;
    available_quantity: number;
    images: ArtworkImage[];
    content_blocks: Block[] | null;
    stock?: number;
    is_published?: boolean;
    is_featured?: boolean;
};
export type Studio = {
    name: string;
    legal_name: string | null;
    tagline: string | null;
    bio: string | null;
    contact_email: string | null;
    phone: string | null;
    address: string | null;
    instagram_url: string | null;
    facebook_url: string | null;
    youtube_url: string | null;
    logo_path: string | null;
    favicon_path: string | null;
    currency: string;
    hero_title: string;
    hero_description: string | null;
    hero_path: string | null;
    portrait_path: string | null;
};
export type Shared = {
    studio: Studio;
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
            role: "admin" | "editor";
        } | null;
    };
    cartCount: number;
    flash: { success?: string; status?: string };
    errors: Record<string, string>;
    [key: string]: unknown;
};
export type Pagination<T> = {
    data: T[];
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
    current_page: number;
    last_page: number;
};
export type CartData = {
    items: {
        product_id: number;
        quantity: number;
        product: Artwork | null;
        max_quantity: number;
        awaiting_availability: boolean;
    }[];
    subtotal_minor: number | null;
};
export const money = (value: number | null, currency: string) =>
    value === null
        ? "Precio a consultar"
        : new Intl.NumberFormat("es-DO", {
              style: "currency",
              currency,
          }).format(value / 100);
export const availability: Record<string, string> = {
    inquiry: "Consultar disponibilidad",
    available: "Disponible",
    reserved: "Reservada",
    sold: "Vendida",
};
export const orderStatuses: Record<string, string> = {
    received: "Recibida",
    awaiting_stock: "En espera de disponibilidad",
    reviewing: "En conversación",
    confirmed: "Confirmada",
    completed: "Completada",
    cancelled: "Cancelada",
};
