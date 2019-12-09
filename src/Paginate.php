<?php
namespace redgoose;

/**
 * Paginate
 * @package redgoose
 *
 * 페이지 네비게이션을 만들어주는 툴
 */

class Paginate {

  /**
   * @var int $total 데이터 총 갯수
   */
  protected $total = 0;

  /**
   * @var int $page 현재 페이지 번호
   */
  protected $page = 1;

  /**
   * @var int $size 한페이지에서 출력되는 데이터 갯수
   */
  protected $size = 10;

  /**
   * @var int $scale 총 페이지 갯수
   */
  protected $scale = 10;

  /**
   * @var int $tails 파라메터로 만들어지는 주소 뒤에붙는 `&foo=bar`형식으로 된 url
   */
  protected $tails = '';

  /**
   * @var int $pageMax 총 페이지갯수
   */
  protected $pageMax = 0;

  /**
   * @var int $offset 해당 페이지에서 시작하는 목록번호
   */
  protected $offset = 0;

  /**
   * @var int $block 페이지를 10개씩보여준다면 1~10페이지까지는 0블럭..
   */
  protected $block = 0;

  /**
   * @var int $no 목록에서 번호나열할때 필요함
   */
  protected $no = 0;

  /**
   * create instance
   * $pref 값 사용에 대해서는 `update()`메서드 참고
   *
   * @param object $pref
   */
  public function __construct($pref=null)
  {
    $this->update($pref);
  }

  /**
   * update properties
   *
   * @param object $pref 필요한 설정값 모음
   *   $pref = (object)[
   *     'total' => int $pref->total total items
   *     'page' => int $pref->page page number
   *     'size' => int $pref->size count of list
   *     'scale' => int $pref->scale count of page
   *     'params' => array $pref->params url parameter
   *   ]
   */
  public function update($pref=null)
  {
    if (isset($pref->total) && $pref->total) $this->total = (int)$pref->total;
    if (isset($pref->page) && $pref->page) $this->page = (int)$pref->page;
    if (isset($pref->size) && $pref->size) $this->size = (int)$pref->size;
    if (isset($pref->scale) && $pref->scale) $this->scale = (int)$pref->scale;

    // calculation
    $this->pageMax = (int)ceil($this->total / $this->size);
    $this->offset = ($this->page - 1) * $this->size;
    $this->block = (int)floor(($this->page - 1) / $this->scale);
    $this->no = $this->total - $this->offset;

    // make tails url
    $tails = '';
    if (isset($pref->params) && is_array($pref->params))
    {
      foreach ($pref->params as $key=>$val)
      {
        $tails .= ($val) ? "{$key}={$val}&" : "";
      }
    }
    $this->tails = (string)substr($tails, 0, -1);
  }

  /**
   * icon element
   * 아이콘이 되는 엘리먼트를 만들어준다.
   *
   * @param string $name
   * @return string
   */
  private static function icon($name)
  {
    $path = null;
    switch ($name)
    {
      case 'prev':
        $path = "<path fill=\"currentColor\" d=\"M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z\"/>";
        break;
      case 'next':
        $path = "<path fill=\"currentColor\" d=\"M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z\"/>";
        break;
      default:
        break;
    }
    return ($path) ? "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\">{$path}</svg>" : '';
  }

  /**
   * create elements
   *
   * @param string $className 클래스 이름
   * @param string $path 링크 앞에붙는 경로주소
   * @return string
   */
  public function createElements($className=null, $path=null)
  {
    $str = '';
    $path = $path ? $path : './';
    $className = $className ? $className : 'paginate';

    if ($this->total > $this->size)
    {
      $str = "<div class=\"$className\">\n";
      // prev
      if ($this->block > 0)
      {
        $prevBlock = (int)(($this->block - 1) * $this->scale + 1);
        $char = ($prevBlock === 1) ? '' : 'page='.$prevBlock;
        $amp = ($this->tails && $char) ? '&' : '';
        $char = ($char || $this->tails) ? '?'.$this->tails.$amp.$char : '';
        $str .= "<a href=\"{$path}{$char}\" title=\"Prev\" class=\"{$className}__prev\">";
        $str .= self::icon('prev');
        $str .= "</a>\n";
      }
      // middle
      $startPage = (int)($this->block * $this->scale + 1);
      for ($i = 1; $i <= $this->scale && $startPage <= $this->pageMax; $i++, $startPage++)
      {
        if ((int)$startPage === $this->page)
        {
          $str .= "<strong>{$startPage}</strong>\n";
        }
        else
        {
          $char = ($startPage === 1) ? '' : "page={$startPage}";
          $amp = ($this->tails && $char) ? '&' : '';
          $char = ($char || $this->tails) ? '?'.$this->tails.$amp.$char : '';
          $str .= "<a href=\"{$path}{$char}\">{$startPage}</a>\n";
        }
      }
      // next
      if ($this->pageMax > ($this->block + 1) * $this->scale)
      {
        $nextBlock = (int)(($this->block + 1) * $this->scale + 1);
        $amp = ($this->tails) ? '&' : '';
        $str .= "<a href=\"{$path}?{$this->tails}{$amp}page={$nextBlock}\" title=\"Next\" class=\"{$className}__next\">";
        $str .= self::icon('next');
        $str .= "</a>\n";
      }
      $str .= "</div>";
    }

    return $str;
  }

  /**
   * create object
   *
   * @param string $path 링크 앞에붙는 경로주소
   * @return object
   */
  public function createObject($path=null)
  {
    $result = null;
    $path = $path ? $path : './';

    if ($this->total > $this->size)
    {
      $result = (object)[
        'prev' => null,
        'body' => null,
        'next' => null,
      ];
      // prev
      if ($this->block > 0)
      {
        $prevBlock = (int)(($this->block - 1) * $this->scale + 1);
        $str = ($prevBlock === 1) ? "" : "page={$prevBlock}";
        $amp = ($this->tails && $str) ? "&" : "";
        $str = ($str || $this->tails) ? '?'.$this->tails.$amp.$str : "";
        $result->prev = (object)[
          'name' => 'prev',
          'no' => $prevBlock,
          'url' => $path.$str,
        ];
      }
      // middle
      $startPage = (int)($this->block * $this->scale + 1);
      for ($i=1; $i<=$this->scale && $startPage<=$this->pageMax; $i++, $startPage++)
      {
        $k = $i - 1;
        if ($startPage === $this->page)
        {
          $result->body[$k] = (object)[
            'name' => $startPage,
            'no' => $startPage,
            'active' => true,
          ];
        }
        else
        {
          $str = ($startPage === 1) ? '' : "page={$startPage}";
          $amp = ($this->tails && $str) ? '&' : '';
          $str = ($str || $this->tails) ? '?'.$this->tails.$amp.$str : '';
          $result->body[$k] = (object)[
            'name' => $startPage,
            'no' => $startPage,
            'url' => $path.$str,
          ];
        }
      }
      //next
      if ($this->pageMax > ($this->block + 1) * $this->scale)
      {
        $nextBlock = (int)(($this->block + 1) * $this->scale + 1);
        $amp = ($this->tails) ? '&' : '';
        $result->next = (object)[
          'name' => 'next',
          'no' => $nextBlock,
          'url' => "{$path}?{$this->tails}{$amp}page={$nextBlock}",
        ];
      }
    }

    return $result;
  }

}
