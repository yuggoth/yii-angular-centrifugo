# yii-angular-centrifugo

1. Нормализовать таблицу и занести все в БД

| ID | Номенклатура | Для чего | Сумма | Организация (наша сторона) | Контрагент | Код затраты | договор | статус |
| -- |:------------:|:--------:|:-----:|:--------------------------:|:----------:|:-----------:|:-------:|:------:|

2. Разработка модуля «Счета»

- добавление счета(статус «на рассмотрении») – поля брать, исходя из п.1
- редактирование счета
- удаление счета(статус «удаленные»)
- прикрепление файлов(прогресс загрузки)
- вывод счетов в список
- перемещение по статусам(2 вкладки – На рассмотрении и Удаленные). Указать количество счетов в каждом статусе.
- карточка счета(инфа+файлы)

3. Динамическое обновление количества счетов во вкладках при помощью centrifugo. https://github.com/centrifugal/centrifugo
Пример: Добавили счет, количество стало +1. Удалили счет. Количество стало -1.

Добавили счет – добавилась строка. Удалили – удалилась. Отредактировали – обновилась

4. На php только бизнес логика(работа с бд и отдача данных ангуляру), Angular - frontend

## Решение

Работает на php7 и apache 2.4

Дамп базы в файле dump.sql, конфиг базы данных по адресу: \config\db.php

Конфиг Centrifugo:

```
{
"secret": "be297686-a482-402c-acc9-2acbca662145",
"anonymous": "true"
}
```

Centrifugo запускать с параметром --insecure (https://fzambia.gitbooks.io/centrifugal/content/mixed/insecure_modes.html)

Файлы загружаются в папку "uploads" в корне проекта.

![](https://image.prntscr.com/image/dLesCsqrToqbVA36hwYQXg.png)
![](https://image.prntscr.com/image/vEeIWaXJQF6Uft5DmfrnAA.png)
