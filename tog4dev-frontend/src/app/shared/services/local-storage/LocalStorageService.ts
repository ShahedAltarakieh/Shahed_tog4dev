import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class LocalStorageService {
  setItem(key: string, value: any): void {
    try {
      localStorage.setItem(key, JSON.stringify(value)); // Store data as a JSON string
    } catch (error) {
    }
  }

  getItem<T>(key: string): T | null {
    try {
      const value = localStorage.getItem(key);
      return value ? JSON.parse(value) : null; // Parse JSON string back to object
    } catch (error) {
      return null;
    }
  }

  removeItem(key: string): void {
    try {
      localStorage.removeItem(key);
    } catch (error) {
    }
  }

  clear(): void {
    try {
      localStorage.clear();
    } catch (error) {
    }
  }
}
