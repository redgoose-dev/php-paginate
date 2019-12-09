# paginate

`[1][2][3]...` 형식으로 된 페이지 네비게이션을 만들어주는 도구입니다.  
한번 실행으로 쉽게 페이지네이션 엘리먼트를 만들거나 객체를 만들어서 사용할 수 있습니다.


## Install

다음과 같이 `composer`를 통하여 패키지를 설치합니다.

```
composer require redgoose/paginate
```

`composer`를 사용하지 않는다면 `github`에서 소스를 다운로드 후에 `/src/Paginate.php`로 사용합니다.


## Usage

### with composer

```php
require 'vendor/autoload.php';
use redgoose\Paginate;

$paginate = new Paginate();
```

### without composer

소스를 다운로드하고 직접 연결할때 사용하는 방법입니다.

```php
require 'src/Paginate.php';
use redgoose\Paginate;

$paginate = new Paginate();
```

### create instance

`new` 키워드를 통하여 인스턴스 객체를 만들어서 사용할 수 있습니다.

```php
$paginate = new Paginate((object)[
  'total' => 300,
  'page' => 1,
]);
```


## Options

인스턴스 객체를 만들때 사용하는 옵션들입니다.  
객체를 만드는 예제소스와 같이 `object` 타입의 객체의 값은 다음과 같습니다.

| Name      | Type  | Default | Description |
| --------- | ----- | ------- | ----------- |
| total     | int   | 0       | 데이터의 총 갯수 |
| page      | int   | 1       | 현재 페이지의 번호 |
| size      | int   | 10      | 한페이지에서 출력되는 데이터 갯수 |
| scale     | int   | 10      | 총 페이지 갯수 |
| params    | array | null    | 파라메터로 만들어지는 주소 뒤에붙는 `&foo=bar`형식으로 된 url |


## Methods

`create instance` 섹션에서 만든 `$paginate` 객체를 통하여 메서드를 사용할 수 있습니다.

### update()

인스턴스 객체를 만들때의 설정값을 변경할 수 있습니다.

```php
$paginate->update($pref);
```

`$pref`값은 `Options`섹션과 같은 값을 사용합니다.

### createElements()

네비게이션 엘리먼트를 만들어줍니다.

```php
$element = $paginate->createElements($path);

echo $element;
```

| Name  | Type   | Default | Description |
| ----- | ------ | ------- | ----------- |
| $path | string | null    | 링크 prefix  |

### createObject()

네비게이션 객체를 만들어줍니다.

```php
$object = $paginate->createObject($path);

print_r($object);
```

| Name  | Type   | Default | Description |
| ----- | ------ | ------- | ----------- |
| $path | string | null    | 링크 prefix  |

객체를 만들면 다음과 같은 모습으로 객체를 리턴합니다.

```
stdClass Object (
  [prev] =>
  [body] => Array (
    [0] => stdClass Object (
      [name] => 1
      [no] => 1
      [url] => ./
    )
    [1] => stdClass Object (
      [name] => 2
      [no] => 2
      [url] => ./?page=2
    )
  )
  [next] => stdClass Object (
    [name] => next
    [no] => 11
    [url] => ./?page=11
  )
)
```