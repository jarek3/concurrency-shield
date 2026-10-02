
# ConcurrencyShield 🛡️

A high-performance, concurrent database optimization component written in pure PHP 8.x. This project demonstrates how to refactor legacy code suffering from performance bottlenecks and transform it into a highly scalable backend solution capable of handling thousands of entities under massive concurrent API traffic.

## 🚀 Key Optimization Features

This project showcases several advanced backend and database optimization techniques designed to minimize hardware resources and maximize response velocity:

* **Elimination of the N+1 Query Problem:** Replaced redundant nested database queries with a single, highly optimized SQL `LEFT JOIN` query combined with smart in-memory data aggregation in PHP.
* **SQLite Write-Ahead Logging (WAL) Mode:** Activated `PRAGMA journal_mode=WAL` to decouple read and write operations. This unlocks concurrent reads without locking the entire database file during transactional writes.
* **Busy Timeout Handling:** Configured `PRAGMA busy_timeout=5000` to prevent application crashes under heavy load, forcing concurrent requests to wait gracefully instead of throwing instant lock exceptions.
* **Natural Sorting Execution:** Implemented natural sorting behavior (`strnatcmp`) in the PHP data-layer to align raw database representations seamlessly with user-facing JSON formats.
* **Security & Defense-in-Depth:** Ensured robust defense against SQL Injection via strict PDO Prepared Statements, and hardened data output formatting against Cross-Site Scripting (XSS).

## 🛠️ Tech Stack & Requirements

- **Language:** PHP >= 8.2 (Strict Types enabled)
- **Database Layer:** Doctrine DBAL 3.x over SQLite 3
- **Testing Environment:** PHPUnit 11.x
- **Development Workflow:** Docker / PHPStorm

## 📦 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com
   cd concurrency-shield
   ```

2. **Install dependencies via Composer:**
   Ensure you have the `zip` extension enabled in your `php.ini`.
   ```bash
   composer install
   ```

3. **Run Automatic Autoloading:**
   ```bash
   composer dump-autoload
   ```

## 🧪 Running the Test Suite

The core optimization logic is fully covered by automated regression tests ensuring that the returned dataset format remains completely unchanged while optimizing execution speed and decreasing query counts.

To execute the PHPUnit test suite on Windows (PowerShell):
```powershell
php vendor/phpunit/phpunit/phpunit tests
```

To execute on Linux / macOS:
```bash
./vendor/bin/phpunit tests
```

## 📈 Performance Results

* **Database Query Overhead:** Reduced from hundreds of sequential queries down to **1 single efficient query**.
* **Memory Management:** Streamlined array allocation utilizing memory-efficient generator hydration where applicable.
* **Concurrency Resistance:** Confirmed stability under synthetic concurrent load simulations without `database is locked` interruptions.