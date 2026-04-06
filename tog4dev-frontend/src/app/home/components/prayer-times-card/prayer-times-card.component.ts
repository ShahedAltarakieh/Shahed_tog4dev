import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CookieService } from 'ngx-cookie-service';
import { TranslatePipe } from '@ngx-translate/core';
import { PrayerSlot, PrayerTimesService } from '../../services/prayer-times.service';
import { StorageService } from 'app/core/storage/storage.service';

@Component({
  selector: 'app-prayer-times-card',
  imports: [TranslatePipe],
  templateUrl: './prayer-times-card.component.html',
  styleUrl: './prayer-times-card.component.scss'
})
export class PrayerTimesCardComponent {
  stars = [1, 2, 3, 4, 5, 6, 7, 8];
  prayerNames: PrayerSlot[] = [
    { name: 'Fajr', time: '--:--' },
    { name: 'Dhuhr', time: '--:--' },
    { name: 'Asr', time: '--:--' },
    { name: 'Maghrib', time: '--:--' },
    { name: 'Isha', time: '--:--' }
  ];
  loading = false;
  timezoneCity = '';
  countryName = '';
  private duas: { ar: string; en: string }[] = [
    {
      ar: 'اللهم إني نويت صيام رمضان إيمانًا واحتسابًا، فاغفر لي ما تقدم من ذنبي وما تأخر.',
      en: 'O Allah, I intend to fast in Ramadan with faith and seeking Your reward, so forgive my past and future sins.'
    },
    {
      ar: 'ذهب الظمأ وابتلت العروق، وثبت الأجر إن شاء الله.',
      en: 'The thirst is gone, the veins are moistened, and the reward is confirmed, if Allah wills.'
    },
    {
      ar: 'اللهم إنك عفو تحب العفو فاعفُ عني.',
      en: 'O Allah, You are Forgiving and love forgiveness, so forgive me.'
    },
    {
      ar: 'اللهم تقبل منا صيامنا وقيامنا وصالح أعمالنا.',
      en: 'O Allah, accept from us our fasting, our standing in prayer, and our righteous deeds.'
    },
    {
      ar: 'اللهم أعني على ذكرك وشكرك وحسن عبادتك.',
      en: 'O Allah, help me to remember You, to thank You, and to worship You in the best manner.'
    }
  ];

  constructor(
    private httpClient: HttpClient,
    private cookieService: CookieService,
    private prayerTimesService: PrayerTimesService,
    public storageService: StorageService
  ) {
    this.loading = true;

    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone; // e.g. "America/Toronto"
    if (timeZone) {
      const parts = timeZone.split('/');
      const cityPart = parts[parts.length - 1] || '';
      this.timezoneCity = cityPart.replace(/_/g, ' ');
    }

    const countryCode = this.cookieService.get('countryCode') || 'JO';
    this.httpClient.get<any[]>('/app/assets/json/countries.json').subscribe({
      next: (countries) => {
        const matched =
          countries.find(c => c.country_code === countryCode) ||
          countries.find(c => c.country_code === 'JO');

        if (matched) {
          // Use English name for the Aladhan API
          this.countryName = matched.country_name_english;
        }

        this.prayerTimesService
          .getTimingsByCountryCode(this.countryName || null, this.timezoneCity || null)
          .subscribe({
            next: (slots) => {
              this.prayerNames = slots;
              this.loading = false;
            },
            error: () => {
              this.loading = false;
            }
          });
      },
      error: () => {
        // If countries.json fails, still try with defaults
        this.prayerTimesService
          .getTimingsByCountryCode(null, null)
          .subscribe({
            next: (slots) => {
              this.prayerNames = slots;
              this.loading = false;
            },
            error: () => {
              this.loading = false;
            }
          });
      }
    });
  }

  get currentDua(): string {
    const isAr = this.storageService.siteLanguage$.value === 'ar';
    const list = this.duas.map(d => (isAr ? d.ar : d.en));
    const separator = '   ⭐   ';
    return list.join(separator);
  }
}
