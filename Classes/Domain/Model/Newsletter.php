<?php
declare(strict_types=1);
namespace In2code\Luxletter\Domain\Model;

use In2code\Luxletter\Domain\Repository\QueueRepository;
use In2code\Luxletter\Utility\ObjectUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class User
 */
class Newsletter extends AbstractEntity
{
    const TABLE_NAME = 'tx_luxletter_domain_model_newsletter';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * @var \DateTime
     */
    protected $datetime = null;

    /**
     * @var string
     */
    protected $subject = '';

    /**
     * @var \In2code\Luxletter\Domain\Model\Usergroup
     */
    protected $receiver = null;

    /**
     * @var string
     */
    protected $origin = '';

    /**
     * @var string
     */
    protected $bodytext = '';

    /**
     * @var null|int
     * @transient
     */
    private $dispatchedProgress = null;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Newsletter
     */
    public function setTitle(string $title): Newsletter
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Newsletter
     */
    public function setDescription(string $description): Newsletter
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return $this
     */
    public function enable()
    {
        $this->disabled = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function disable()
    {
        $this->disabled = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->disabled === false;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime(): \DateTime
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     * @return Newsletter
     */
    public function setDatetime(\DateTime $datetime): Newsletter
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Newsletter
     */
    public function setSubject(string $subject): Newsletter
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return Usergroup
     */
    public function getReceiver(): Usergroup
    {
        return $this->receiver;
    }

    /**
     * @param Usergroup $receiver
     * @return Newsletter
     */
    public function setReceiver(Usergroup $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return Newsletter
     */
    public function setOrigin(string $origin): Newsletter
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return string
     */
    public function getBodytext(): string
    {
        return $this->bodytext;
    }

    /**
     * @param string $bodytext
     * @return Newsletter
     */
    public function setBodytext(string $bodytext): Newsletter
    {
        $this->bodytext = $bodytext;
        return $this;
    }

    /**
     * Checks the queue progress of this newsletter. 100 means 100% are sent.
     *
     * @return int
     */
    public function getDispatchProgress(): int
    {
        if ($this->dispatchedProgress === null) {
            $queueRepository = ObjectUtility::getObjectManager()->get(QueueRepository::class);
            $dispatched = $queueRepository->findDispatchedNewsletters($this)->count();
            $notDispatched = $queueRepository->findNotDispatchedNewsletters($this)->count();
            $overall = $dispatched + $notDispatched;
            $result = 0;
            if ($overall > 0) {
                $result = (int)(100 - ($notDispatched / $overall * 100));
            }
            $this->dispatchedProgress = $result;
        }
        return $this->dispatchedProgress;
    }
}
