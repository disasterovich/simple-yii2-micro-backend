<?php
namespace micro\models;

use Yii;
use micro\models\Order;
use micro\models\Client;
use micro\models\Departure;

class OrderForm extends \yii\base\Model
    {
    /**
     * @var string
     */
    public $clientName;

    /**
     * @var string
     */
    public $clientPhone;

    /**
     * @var string
     */
    public $departureAddress;

    /**
     * @var string
     */
    public $departureFromDate;

    /**
     * @var string
     */
    public $departureFromTime;

    /**
     * @var string
     */
    public $departureToDate;

    /**
     * @var string
     */
    public $departureToTime;

    /**
     * @var Client
     */
    protected $_client;

    /**
     * @var Departure
     */
    protected $_departure;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * OrderForm constructor.
     * @param Order $order
     * @param array $config
     */
    public function __construct(Order $order = null, array $config = [])
    {
        if (is_null($order)) {
            $this->_order = new Order();
            $this->_client = new Client();
            $this->_departure = new Departure();
        } else {
            $this->_order = $order;
            $this->_client = $order->client;
            $this->_departure = $order->departure;

            $this->clientName = $this->_client->name;
            $this->clientPhone = $this->_client->phone;

            $this->departureAddress = $this->_departure->address;
            $this->departureFromTime = Yii::$app->formatter->asTime($this->_departure->from, 'php:H:i');
            $this->departureFromDate = Yii::$app->formatter->asDate($this->_departure->from, 'php:Y-m-d');
            $this->departureToTime = Yii::$app->formatter->asTime($this->_departure->to, 'php:H:i');
            $this->departureToDate = Yii::$app->formatter->asDate($this->_departure->to, 'php:Y-m-d');
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
        {
        return [
            [[
                'clientName',
                'clientPhone',
                'departureAddress',
                'departureFromDate',
                'departureFromTime',
                'departureToDate',
                'departureToTime',
            ], 'required'],

            [[
                'clientName',
                'clientPhone',
                'departureAddress',
                'departureFromDate',
                'departureFromTime',
                'departureToDate',
                'departureToTime',
            ], 'string', 'max' => 200 ],
        ];
        }

    /**
     * Save model
     *
     * @return bool
     */
    public function save ()
    {
        if(!$this->validate()) {
            return false;
        }

        $this->_client->name = $this->clientName;
        $this->_client->phone = $this->clientPhone;

        $this->_departure->address = $this->departureAddress;
        $this->_departure->from = Yii::$app->formatter->asTimestamp($this->departureFromDate.' '.$this->departureFromTime.' '.Yii::$app->timeZone);
        $this->_departure->to = Yii::$app->formatter->asTimestamp($this->departureToDate.' '.$this->departureToTime.' '.Yii::$app->timeZone);

        $transaction = Yii::$app->db->beginTransaction();

        try {

            if (!$this->_client->save(false)) {
                throw new \yii\base\ErrorException("Клиент не может быть сохранен.");
            }

            if (!$this->_departure->save(false)) {
                throw new \yii\base\ErrorException("Выезд не может быть сохранен.");
            }

            if ($this->_order->isNewRecord) {
                $this->_order->client_id = $this->_client->id;
                $this->_order->departure_id = $this->_departure->id;

                if (!$this->_order->save(false)) {
                    throw new \yii\base\ErrorException("Заказ не может быть сохранен.");
                }
            }

            $transaction->commit();

            return true;

        } catch (\Throwable $ex) {
            $transaction->rollBack();
        }

        return false;
    }

    }
