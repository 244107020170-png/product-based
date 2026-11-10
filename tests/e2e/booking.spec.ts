import { test, expect } from '@playwright/test';
import { LoginPage } from './pages/LoginPage';
import { BookingPage } from './pages/BookingPage';

test('pengguna berhasil melihat daftar dan detail pemesanan', async ({ page }) => {
  const loginPage = new LoginPage(page);
  const bookingPage = new BookingPage(page);

  // 1. Login ke aplikasi
  await loginPage.open();

  await loginPage.login(
    process.env.E2E_USERNAME!,
    process.env.E2E_PASSWORD!
  );

  await expect(page).toHaveURL(/\/dashboard$/);

  // 2. Membuka daftar pemesanan
  await bookingPage.open();

  await expect(page).toHaveURL(/\/bookings$/);

  // 3. Memastikan terdapat pemesanan yang bisa dibuka
  await expect(bookingPage.detailLink.first()).toBeVisible();

  // 4. Membuka detail pemesanan
  await bookingPage.openFirstBookingDetail();

  // 5. Memastikan halaman detail berhasil ditampilkan
  await expect(bookingPage.detailHeading).toBeVisible();
  await expect(page.getByText('ID Pemesanan:', { exact: false })).toBeVisible();
  await expect(page.getByText('Tanggal Bermain')).toBeVisible();
  await expect(page.getByText('Detail Pembayaran')).toBeVisible();

  await expect(page).toHaveURL(/\/bookings\/\d+$/);
});
