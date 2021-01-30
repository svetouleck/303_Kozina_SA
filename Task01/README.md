## Описание структуры файлов данных

### genres.txt
    Поля:
        genres: object;

    Структура: линейная
    Количество строк: 18

### movies.csv
    Поля:
        movieId: int64;
        title:   object;
        genres:  object;

    Структура: табличная
    Разделитель: ','
    Количество строк: 9742

### occupation.txt
    Поля:
        occupation: object;

    Структура: линейная
    Количество строк: 21

### ratings.csv
     Поля:
        userId:    int64;
        movieId:   int64;
        rating:    float64;
        timestamp: int64;  

    Структура: табличная
    Разделитель: ','
    Количество строк: 18938

### ratings_count.txt
    Поля:
        id:     int64;
        counts: int64;

    Структура: линейная
    Количество строк: 2

### tags.csv
     Поля:
        userId:    int64;
        movieId:   int64;
        tag:       object;
        timestamp: int64;  

    Структура: табличная
    Разделитель: ','
    Количество строк: 3683

### users.txt
    Поля:
        id:         int64;
        name:       int64;
        email:      object;
        sex:        object;
        date:       object;
        profession: object;

    Структура: табличная
    Разделитель: '|'
    Количество строк: 942
