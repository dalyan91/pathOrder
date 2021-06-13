Order
========================

İndirim Tablosu 'discount':
--------------
Tabloda yer alan sütunları açıklamak istedim.

* **category**: Kategori seçilerek o kategoriye ait ürünler için indirim tanılamak için kullandım;
* **code**: İndirime özel kod alanı ;
* **operationType**: Toplamdan mı indirim yapılacak adeten mi belirlemek için. 
    * adet: unit
    * toplam: total
* **operationUnit**: Koşul kısmı burada operationType değeri total para birimi, unit ise adet sayısı girilöeli.
* **discountType**: Koşulu sağladığında yapılacak indirim tipi.
    * yüzde : percent
    * birim, adet : unit
* **discount**: discountType değeri percent ise yüzde rakamı, unit ise adet sayı sı girilmeli.

**NOT**: Her kategori için ayrı ayrı indirim gidirilebilir şekilde ayarlandı. Aynı kategori için birden fazla indirim girilmesi kapalı açılabilir. 
Toplamdan indirim için de aynı şekilde 1 koşul sağlanabilir şeklinde ayarlandı.

Postman Workspace
--------------
Posrman üzerinden yapmış olduğum çalışmayı paylaşıyorum. 

**collection** : https://www.getpostman.com/collections/ce98bea404d6a194bba6
**environment** : /order.postman_environment.json
