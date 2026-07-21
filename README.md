<p align="center"><a href="https://textbrew.ai/" target="_blank"><img src="https://cdn.prod.website-files.com/6422f4c111e30d1daa9dd518/6458f75dc19e765418cce46c_logo_color_bgtransparent_h_cropped.svg" width="400" alt="TextBrew Logo"></a></p>

# TextBrew Backend & Hardware Monitoring

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/License-Proprietary-red?style=for-the-badge" alt="License">
</p>

## About TextBrew

TextBrew is the automated product description generator that simplifies your life and scales your e-commerce content operations. This repository houses the core backend service, queue management infrastructure, and the newly integrated **Poiza Hardware Order Monitoring System**.

---

## 🚀 Poiza Hardware Order Monitoring System

The Poiza integration tracks and syncs hardware orders seamlessly within the TextBrew backend infrastructure.

### 1. Environment Configuration
Ensure your `.env` file includes the correct API endpoints and database credentials for the Poiza hardware tracking service:
```env
POIZA_API_URL=https://api.poiza.hardware.com/v1
POIZA_WEBHOOK_SECRET=your_webhook_secret_here
