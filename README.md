Order
========================

## Installation
  Proje gereksinimleri yüklemek için terminalden proje dizinine gelip aşağıdaki kodu çalıştırılmalı.

    php composer.phar install
**Veritabanı ayar ve dataların yüklenmesi için**;
1. Proje dizininde yer alan `/app/config/parameters.yml.dist` dosyasını kopyalanıp aynı dizine `/app/config/parameters.yml` olarak yeniden adlandırılmalı.
2. `/app/config/parameters.yml` içerisinde yer alan veritabanı bağlantı ve kullanıcı bilgilerini bilgisayarınızın veritabanı bağlantı bilgilerine göre düzenliyoruz.
3. Terminal `php bin/console d:d:c` kodu çalıştırarak veritabanını oluşturuyoruz.
4. Veritabanı tablolarını oluşturmak için terminalden `php bin/console d:s:u --force` kodu çalıştırıyoruz.
5. Tabloların içini gerekli dataları yüklemek için terminalden `php bin/console d:f:l` kodu çalıştırıyoruz.
6. Projeyi çalışır hale getirmek için terminalden `php bin/console s:r` kodu çalıştırıyoruz. Burada oluşan http ile başlayan linki Postman da kullanmak için saklıyoruz.

Proje kurulumu ve gereksinimlerin yüklenmesi tamam. Artık api test istekleri atılabilir durumda.

Postman çalışma alanı
--------------
1. Postman import kısmından link kısmına tıklanarak aşağıdaki collection linkini yazarak çalışmalarımıza dahil edebiliriz.

**collection** : https://www.getpostman.com/collections/df2df4bd22cd23becd64

2. Postman import kısmından link kısmına tıklanarak aşağıdaki environment linkini yazarak çalışmalarımıza dahil edebiliriz.

**environment** : https://raw.githubusercontent.com/dalyan91/pathOrder/main/pathOrder.postman_environment.json

3. İki import işlemini gerçekleştirdikten sonra environment seçeneğinden pathOrder seçilmeli.

4. Projeyi çalıştırdığımızda elde ettiğimiz linki environment kısmında yer alan domain alanına yazıyoruz.

5. pathOrder çalışma alanında yer alan **Login** kısmına veritabanından yer alan account tablosundan email alınıp parolası 12345 olarak token isteği atılarak token elde edilmeli.

6. Elde ettiğimiz tokenı environment kısmındaki token alanına yazıyoruz.

Postman ayarlarımız hazır. Artık çalışmalarımızdaki  order kullanılarak siparişimizi oluşturabilriiz.


Sipariş işlemleri
--------------
1. Postman çalışma alanımızda yer alan Product bölümündeki list kısmına istek atarak sipariş vermek istediğimiz ürünün id alıyoruz.
2. Postman çalışma alanımızda yer alan Order bölümündeki new kısmının body içerisindeki form alanında yer alan product fieldine 1. aşamadaki aldığımız ürün id yerleştiriyoruz.
3. Sipariş için gerekli diğer alanları doldurduktan sonra send butonu ile verileri gönderebiliriz.
4. Postman çalışma alanımızda yer alan Order bölümündeki list kısmından verdiğimiz siparişlerin listesini görüntüleye bilir ve buradan elde edeceğimiz id ile get methodu kullanılarak sipariş detayını görüntüleyebilir veya update ile siparişimizi güncelleyebiliriz.
5. Siraşimizi güncelleyebilmek için shippingDate şuandan büyük olmalı.
6. Oturum açan kişi kendi firmasının sipariş listesini görüntüleyebilir, düzenleyebilir ve oluşturabilir.