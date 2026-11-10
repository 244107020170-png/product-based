
import { test, expect } from '@playwright/test';
import { LoginPage } from './pages/LoginPage';

test('pengguna berhasil login dan masuk ke dashboard', async ({ page }) => {
  const loginPage = new LoginPage(page);

  await loginPage.open();

  await expect(loginPage.heading).toBeVisible();

  await loginPage.login(
    process.env.E2E_USERNAME!,
    process.env.E2E_PASSWORD!
  );

  await expect(page).toHaveURL(/\/dashboard$/);
});
