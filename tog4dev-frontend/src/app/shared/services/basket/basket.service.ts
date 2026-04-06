import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class BasketService {
  quantity: number = 0;
  constructor() { }

}
