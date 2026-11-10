import { Page, Locator } from '@playwright/test';

export class BookingPage {
  readonly page: Page;
  readonly detailLink: Locator;
  readonly detailHeading: Locator;

  constructor(page: Page) {
    this.page = page;

    this.detailLink = page.getByRole('link', {
      name: 'Lihat Detail Pemesanan',
      exact: true,
    });

    this.detailHeading = page.getByRole('heading', {
      name: 'Detail Pemesanan',
      exact: true,
    });
  }

  async open() {
    await this.page.goto('/bookings');
  }

  async openFirstBookingDetail() {
    await this.detailLink.first().click();
  }
}
