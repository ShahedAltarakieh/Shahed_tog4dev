import { Injectable } from '@angular/core';

declare global {
  interface Window {
    fbq?: (...args: any[]) => void;
  }
}

interface ProductViewEventBase {
  productId: string | number;
  name: string;
  category?: string;
  price?: number;
}

interface ProductEventBase {
  productId: string | number;
  name: string;
  category?: string;
  price?: number;     // single unit price
  quantity?: number;  // optional, default 1
}

type CartItem = {
  productId: string | number;
  price?: number;     // unit price
  quantity?: number;  // default 1
  [k: string]: any;   // sku, brand, etc. (optional)
};

type ExtraParams = Record<string, any>;

@Injectable({ providedIn: 'root' })
export class MetaPixelService {
  private get fbq() {
    return typeof window !== 'undefined' ? window.fbq : undefined;
  }

  trackViewContentProduct(
    data: ProductViewEventBase,
    extras: ExtraParams = {}
  ) {
    if (!this.fbq) {
      console.warn('Meta Pixel (fbq) not found');
      return;
    }

    const payload = {
      content_ids: [data.productId],
      content_type: 'product',
      content_name: data.name,
      content_category: data.category,
      value: data.price ?? 0,
      currency: 'JOD',
      ...extras
    };

    this.fbq('track', 'ViewContent', payload);
  }

  trackAddToCartProduct(data: ProductEventBase, extras: ExtraParams = {}) {
    if (!this.fbq) return;

    const quantity = data.quantity ?? 1;
    const value = data.price;

    this.fbq('track', 'AddToCart', {
      content_ids: [data.productId],
      content_type: 'product',
      content_name: data.name,
      content_category: data.category,
      currency: 'JOD',
      value,
      contents: [
        {
          id: data.productId,
          quantity,
          item_price: (data.price) ? data.price / quantity :  0
        }
      ],
      ...extras
    });
  }

  trackCartView(
    items: CartItem[],
    extras: ExtraParams = {},
    alsoCustom: boolean = true
  ) {
    if (!this.fbq) { console.warn('Meta Pixel (fbq) not found'); return; }
  
    const contents = items.map(i => ({
      id: i.productId,
      quantity: Math.max(1, i.quantity ?? 1),
      item_price: i.price ?? 0
    }));
  
    const value = contents.reduce((sum, c) => sum + (c.item_price * c.quantity), 0);

    // (Optional) Custom event for your own reporting
    if (alsoCustom) {
      this.fbq!('trackCustom', 'ViewCart', {
        currency: 'JOD',
        value,
        contents,
        ...extras
      });
    }
  }  

  /** InitiateCheckout for the whole cart */
  trackInitiateCheckout(
    items: CartItem[],
    extras: ExtraParams = {}
  ) {
    if (!this.fbq) { console.warn('Meta Pixel (fbq) not found'); return; }

    const contents = items.map(i => ({
      id: i.productId,
      quantity: Math.max(1, i.quantity ?? 1),
      item_price: i.price ?? 0
    }));

    const value = contents.reduce((sum, c) => sum + (c.item_price * c.quantity), 0);
    const num_items = contents.reduce((n, c) => n + c.quantity, 0);

    this.fbq!('track', 'InitiateCheckout', {
      content_type: 'product',
      content_ids: contents.map(c => c.id),
      contents,
      value,
      currency: 'JOD',
      num_items,
      // Optional extras you may pass: coupon, payment_method, shipping_tier, etc.
      ...extras
    });
  }

  /** Purchase (order completed) */
  trackPurchase(
    orderId: string | number,
    items: CartItem[],
    extras: ExtraParams = {}
  ) {
    if (!this.fbq) { console.warn('Meta Pixel (fbq) not found'); return; }

    // Build contents from your cart shape
    const contents = items.map(i => ({
      id: i.productId,
      quantity: Math.max(1, i.quantity ?? 1),
      item_price: i.price ?? 0
    }));

    const subtotal = contents.reduce((s, c) => s + c.item_price * c.quantity, 0);

    const value = Math.max(0, subtotal);
    const num_items = contents.reduce((n, c) => n + c.quantity, 0);

    const eventID = 'ev_' + Math.random().toString(36).slice(2) + Date.now().toString(36);

    this.fbq!('track', 'Purchase', {
      content_type: 'product',
      content_ids: contents.map(c => c.id),
      contents,
      value,
      currency: 'JOD',
      num_items,
      order_id: String(orderId),
      ...extras 
    }, { eventID });
  }

}
