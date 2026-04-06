import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, catchError, of, switchMap } from 'rxjs';

export interface PrayerSlot {
  name: string;
  time: string;
  ampm?: 'AM' | 'PM';
}

const DEFAULT_LOCATION = { city: 'Amman', country: 'Jordan' };
const ALADHAN_BASE = 'https://api.aladhan.com/v1';

@Injectable({
  providedIn: 'root'
})
export class PrayerTimesService {

  constructor(private http: HttpClient) {}
  /**
   * Get today's prayer times for a specific city and country.
   * If city or country are missing, DEFAULT_LOCATION is used.
   */
  getTimingsByCountryCode(
    city: string | null | undefined,
    country: string | null | undefined
  ) {
    const finalCity = city?.trim() || DEFAULT_LOCATION.city;
    const finalCountry = country?.trim() || DEFAULT_LOCATION.country;
    const url = `${ALADHAN_BASE}/timingsByCity?city=${encodeURIComponent(finalCity)}&country=${encodeURIComponent(finalCountry)}`;
    return this.http.get<{
      data?: { timings?: Record<string, string> };
    }>(url).pipe(
      map(res => this.mapTimingsToSlots(res.data?.timings)),
      catchError(() => of(this.getPlaceholderSlots()))
    );
  }

  private mapTimingsToSlots(timings: Record<string, string> | undefined): PrayerSlot[] {
    if (!timings) return this.getPlaceholderSlots();
    const keys: (keyof typeof timings)[] = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];
    return keys.map(name => this.to12hSlot(name, timings[name]));
  }

  /** Convert 24h time to 12h slot (time + AM/PM). */
  private to12hSlot(name: string, raw: string | undefined): PrayerSlot {
    const parsed = this.to12h(raw);
    if (!parsed) return { name, time: '--:--' };
    return { name, time: parsed.time, ampm: parsed.ampm };
  }

  /** Strip timezone suffix and convert "HH:mm" (24h) to { time: "h:mm", ampm: "AM"|"PM" }. */
  private to12h(raw: string | undefined): { time: string; ampm: 'AM' | 'PM' } | null {
    if (!raw) return null;
    const match = raw.match(/^(\d{1,2}):(\d{2})/);
    if (!match) return null;
    let hours = parseInt(match[1], 10);
    const minutes = match[2];
    const isPm = hours >= 12;
    if (hours === 0) hours = 12;
    else if (hours > 12) hours -= 12;
    const time = `${hours}:${minutes}`;
    return { time, ampm: isPm ? 'PM' : 'AM' };
  }

  private getPlaceholderSlots(): PrayerSlot[] {
    return [
      { name: 'Fajr', time: '--:--' },
      { name: 'Dhuhr', time: '--:--' },
      { name: 'Asr', time: '--:--' },
      { name: 'Maghrib', time: '--:--' },
      { name: 'Isha', time: '--:--' }
    ];
  }
}
