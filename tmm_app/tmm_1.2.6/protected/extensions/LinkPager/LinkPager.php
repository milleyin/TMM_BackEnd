<?php
class LinkPager extends CLinkPager
{
	public $selectedPageLabel;
	public $pageCountPageLabel;
	public $pageVarPageLabel;
	public $pageLinkPageLabel;	
	
	public $selectedPage;
	public $pageVar;
	
	public $domain;
	/**
	 * Initializes the pager by setting some default property values.
	*/
	public function init()
	{
		if($this->nextPageLabel===null)
			$this->nextPageLabel='next';
		if($this->selectedPageLabel===null)
			$this->selectedPageLabel='selected';
		if($this->prevPageLabel===null)
			$this->prevPageLabel='prev';
		if($this->pageCountPageLabel===null)
			$this->pageCountPageLabel='count';
		if($this->pageVarPageLabel===null)
			$this->pageVarPageLabel='page';
		if($this->pageLinkPageLabel===null)
			$this->pageLinkPageLabel='link';
		if($this->selectedPage===null)
			$this->selectedPage='selectedPage';
		$this->pageVar=$this->pages->pageVar;
	}

	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function run()
	{
		$this->registerClientScript();
		$buttons=$this->createPageButtons();
		return $buttons;	
	}

	/**
	 * Creates the page buttons.
	 * @return array a list of page buttons (in HTML code).
	 */
	protected function createPageButtons()
	{
		$buttons=array();
		if(($pageCount=$this->getPageCount())<=1)
		{
			if ($this->prevPageLabel !== false) 
				$buttons[$this->prevPageLabel]='';
			if ($this->selectedPageLabel !== false)
				$buttons[$this->selectedPageLabel]='';
			if ($this->nextPageLabel !== false) 
				$buttons[$this->nextPageLabel]='';
			if ($this->pageCountPageLabel !== false)
				$buttons[$this->pageCountPageLabel]=1;
			if ($this->pageVarPageLabel !== false)
				$buttons[$this->pageVarPageLabel]=$this->pageVar;
			if ($this->pageLinkPageLabel !== false)
				$buttons[$this->pageLinkPageLabel]=$this->createPageButton($this->prevPageLabel,0,$this->previousPageCssClass,false,false);
			if($this->selectedPage !==false)
				$buttons[$this->selectedPage]=1;
			return 	$buttons;
		}
		list($beginPage,$endPage)=$this->getPageRange();
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
	
		if ($this->prevPageLabel !== false) {
			if(($page=$currentPage-1)<0)
				$buttons[$this->prevPageLabel]='';
			else 
				$buttons[$this->prevPageLabel]=$this->createPageButton($this->prevPageLabel,$page,$this->previousPageCssClass,$currentPage<=0,false);	
		}
		// internal pages		
		if ($this->selectedPageLabel !== false)
			$buttons[$this->selectedPageLabel]=$this->createPageButton($currentPage+1,$currentPage,$this->internalPageCssClass,false,$currentPage);		
		// next page
		if ($this->nextPageLabel !== false) {
			if(($page=$currentPage+1)>=$pageCount)
				$buttons[$this->nextPageLabel]='';
			else
				$buttons[$this->nextPageLabel]=$this->createPageButton($this->nextPageLabel,$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false);
		}	
		if($this->selectedPage !==false)
			$buttons[$this->selectedPage]=$page;
		if ($this->pageCountPageLabel !== false)
			$buttons[$this->pageCountPageLabel]=$pageCount;
		if ($this->pageVarPageLabel !== false)
			$buttons[$this->pageVarPageLabel]=$this->pageVar;
		if ($this->pageLinkPageLabel !== false)
			$buttons[$this->pageLinkPageLabel]=$this->createPageButton($this->prevPageLabel,0,$this->previousPageCssClass,false,false);
		
		return $buttons;
	}

	/**
	 * Creates a page button.
	 * You may override this method to customize the page buttons.
	 * @param string $label the text label for the button
	 * @param integer $page the page number
	 * @param string $class the CSS class for the page button.
	 * @param boolean $hidden whether this page button is visible
	 * @param boolean $selected whether this page button is selected
	 * @return string the generated button
	 */
	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		return $this->domain.$this->createPageUrl($page);
	}

	/**
	 * @return array the begin and end pages that need to be displayed.
	 */
	protected function getPageRange()
	{
		$currentPage=$this->getCurrentPage();
		$pageCount=$this->getPageCount();

		$beginPage=max(0, $currentPage-(int)($this->maxButtonCount/2));
		if(($endPage=$beginPage+$this->maxButtonCount-1)>=$pageCount)
		{
			$endPage=$pageCount-1;
			$beginPage=max(0,$endPage-$this->maxButtonCount+1);
		}
		return array($beginPage,$endPage);
	}
}