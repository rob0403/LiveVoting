<?php
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/voting/display/class.xlvoBarGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/voting/display/class.xlvoBarCollectionGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/voting/display/class.xlvoBarPercentageGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/voting/display/class.xlvoBarOptionGUI.php');

class xlvoDisplayVotingGUI {

	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var xlvoVoting
	 */
	protected $voting;
	protected $answer_count = 64;


	/**
	 * @param xlvoVoting $voting
	 */
	public function __construct(xlvoVoting $voting) {
		$this->voting = $voting;
		$this->tpl = new ilTemplate('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/templates/default/voting/display/tpl.display_voting.html', false, false);
	}


	protected function render() {

		switch ($this->voting->getVotingType()) {
			case xlvoVotingType::SINGLE_VOTE:
				$this->tpl->setVariable('OPTION_CONTENT', $this->renderSingleVote());
				break;
		}

		$this->tpl->setVariable('TITLE', $this->voting->getTitle());
		$this->tpl->setVariable('QUESTION', $this->voting->getQuestion());
	}


	/**
	 * @return string
	 */
	public function getHTML() {
		$this->render();

		return $this->tpl->get();
	}


	/**
	 * @param xlvoOption $option
	 */
	protected function addAnswer(xlvoOption $option) {
		$this->tpl->setCurrentBlock('option');
		$this->tpl->setVariable('OPTION_LETTER', (chr($this->answer_count)));
		$this->tpl->setVariable('OPTION_TEXT', $option->getText());
		$this->tpl->parseCurrentBlock();
	}


	protected function renderSingleVote() {
		$bars = new xlvoBarCollectionGUI();
		foreach ($this->voting->getVotingOptions()->get() as $option) {
			$this->answer_count ++;
			$bars->addBar(new xlvoBarOptionGUI($this->voting, $option, (chr($this->answer_count))));
			$this->addAnswer($option);
		}

		return $bars->getHTML();
	}
}