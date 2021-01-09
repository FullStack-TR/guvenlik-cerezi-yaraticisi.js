# guvenlik-cerezi-yaraticisi.js

## Yapılacaklar
1. [ ] Çerezin yazılmasına engel olacak sunucu taraflı hatalar irdelenmiyor.

## Tasarım
Mozilla Firefox için tasarlanmıştır.
Mozilla Firefox 84.0.2 (32 bit) ve PHP 7.1 ile sorunsuz çalışmaktadır.

## Nedir?
Web tabanlı işlemlerde güvenlik amacıyla kullanılacak güvenlik çerezi oluşturur.


# Ne Kullanır?
https protokolü zorunlu tutulmuştur.

Tarayıcı tarafında  [Kriptografik_Guclu_Anahtar_Yaratici.js](https://github.com/FullStack-TR/Kriptografik_Guclu_Anahtar_Yaratici.js), sunucu tarafında ise az bir miktar ```php``` kullanır.


## Ne Yapar?
Tarayıcı tarafında oluşturulan kriptografik açıdan güçlü 32 basamaklı ```string``` türündeki metni sunucuya göndererek bu değerin tarayıcıda çerez olarak yazılmasını sağlar.


## Güvenlik
Çerezde; ```path=/```, ```secure```, ```HttpOnly```, ```SameSite=Lax``` bayrakları kullanılmıştır.


## Yapılandırma
Çerez geçerlilik süresi ```40``` dakikadır ve gerekli işlemin gerçekleşmesi için en az ```2``` saniye beklenmelidir.

Çerez adı olarak ```acar``` kullanılmıştır.

Bu değerler ```php``` kodunun başında bulunur ve kolaylıkla değiştirilebilir.


## Nasıl Çalışır?
1. Sayfayı sunucunuza atıp sayfayı tarayıcınızdan açın.
2. Sayfa açılınca 2 saniye bekleyin. ( Beklemeniz gereken süre en üstte işlem çubuğunda gösterilir ve tümüyle dolduğunda kopyalama işlemi gerçekleştirilebilir. )
3. ```Açarı Kopyala``` tuşu yeşil olunca, tuşa basın.
4. Sunucu yanıtı gelince tarayıcı uyarı verecek.
5. Uyarı geldiğinde, güvenlik amacıyla kullanabileceğiniz 32 basamaklı kod, tarayıcınıza çerez olarak yazılmış ve bilgisayarınız belleğine kopyalanmış demektir.
6. Çerez sınamasının yapıldığı dosyayı açıp 32 basamaklı çerez değeri olarak yazıp sunucu tarafındaki dosyayı güncelleyin.

Böylelikle bu değer sahip olmayan hiç bir tarayıcı çerez güvenliğinden geçemeyecektir.

## Dikkat !

Çerez yazımından önce çok sayıda HMTL içeriğinin olması sonucunda ```HTTP Başlıkları Zaten Yazıldı.``` hatası alırsınız. Böyle bir durumda çereziniz yazılmayacaktır. Çerez yazımı sırasında tarayıcı panelinizi açarak çerezin doğru bir biçimde yazıldığından emin olun.
