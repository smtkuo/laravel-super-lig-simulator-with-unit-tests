
# Laravel Süper Lig Simulator

Laravel Süper Lig Simulator, futbol turnuvalarını simüle etmek ve takımların performanslarını değerlendirmek için Laravel 10 kullanılarak oluşturulmuş bir web uygulamasıdır. Bu proje, takımlar arasındaki maçları simüle eder, puan durumlarını günceller ve şampiyonluk tahminlerinde bulunur.

## Kurulum

Projeyi yerel ortamınızda çalıştırmak için aşağıdaki adımları takip edin.

### Önkoşullar

- PHP 8.2 veya daha yeni bir sürüm
- Composer
- MySQL veya PostgreSQL

### Laravel 10 Kurulumu

1. Projeyi klonlayın:
   ```sh
   git clone https://github.com/smtkuo/laravel-super-lig-simulator-with-unit-tests
   ```

2. Bağımlılıkları kurun:
   ```sh
   cd laravel-super-lig-simulator-with-unit-tests
   composer install
   ```

3. `.env` dosyasını oluşturun ve veritabanı ayarlarınızı yapın:
   ```sh
   cp .env.example .env
   ```

4. Uygulama anahtarını oluşturun:
   ```sh
   php artisan key:generate
   ```

5. Veritabanını oluşturun ve migration'ları çalıştırın:
   ```sh
   php artisan migrate
   ```

### Veritabanı Seeding

Uygulama ile birlikte gelen örnek verileri veritabanınıza eklemek için:
```sh
php artisan db:seed
```

## PHPUnit Testleri

Projenin birim testlerini çalıştırmak için aşağıdaki komutu kullanın:
```sh
vendor/bin/phpunit
```

## Proje Görselleri

Projenin ekran görüntüleri ve kullanıcı arayüzü örnekleri.

![Image](/images/project1.png)

![Image](/images/project2.png)

![Image](/images/project3.png)

![Image](/images/project4.png)

![Image](/images/project5.png)

![Image](/images/project6.png)