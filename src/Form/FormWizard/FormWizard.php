<?php
namespace Drupal\zemoga_form_wizard\Form\FormWizard;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class FormWizard extends FormBase
{

    /**
     * @var \Drupal\user\PrivateTempStoreFactory
     */
    protected $tempStoreFactory;

    /**
     * @var \Drupal\Core\Session\SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var \Drupal\Core\Session\AccountInterface
     */
    private $currentUser;

    /**
     * @var \Drupal\user\PrivateTempStore
     */
    protected $store;

    /**
     * Constructs a \Drupal\demo\Form\Multistep\MultistepFormBase.
     *
     * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
     * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
     * @param \Drupal\Core\Session\AccountInterface $current_user
     */
    public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user)
    {
        $this->tempStoreFactory = $temp_store_factory;
        $this->sessionManager = $session_manager;
        $this->currentUser = $current_user;

        $this->store = $this->tempStoreFactory->get('multistep_data');
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('user.private_tempstore'), $container->get('session_manager'), $container->get('current_user')
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // Start a manual session for anonymous users.
        if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
            $_SESSION['multistep_form_holds_session'] = true;
            $this->sessionManager->start();
        }

        $form = array();
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#button_type' => 'primary',
            '#weight' => 10,
        );

        return $form;
    }

    /**
     * Saves the data from the multistep form.
     */
    protected function saveData()
    {
        $data = array(
            'first_name' => $this->store->get('first_name'),
            'last_name' => $this->store->get('last_name'),
            'gender' => $this->store->get('gender'),
            'birthday' => $this->store->get('birthday'),
            'city' => $this->store->get('city'),
            'phone' => $this->store->get('phone'),
            'address' => $this->store->get('address'),
            'user' => $this->store->get('user'),
            'password' => $this->store->get('password'),
            'creation_date' => date('Y-m-d H:i:s'),
        );

        // Create user in system
        $user_id = $this->createUser($data);
        $data['id_user'] = $user_id;

        // Save data in custom table
        $record = $this->setZemogaUser($data);

        // Clear session data
        $this->deleteStore();
        
        //Notify to user
        drupal_set_message($this->t('The form has been saved. Information: <br> '
                . 'Id: ' . $record . '<br>'
                . 'First name: ' . $data['first_name'] . '<br>'
                . 'Last name: ' . $data['last_name'] . '<br>'
                . 'Gender: ' . $data['gender'] . '<br>'
                . 'Birthday: ' . $data['birthday'] . '<br>'
                . 'City: ' . $data['city'] . '<br>'
                . 'Phone: ' . $data['phone'] . '<br>'
                . 'Address: ' . $data['address'] . '<br>'
                . 'User: ' . $data['user'] . '<br>'
                . 'Password: ' . $data['password'] . '<br>'
                . 'Creation Date: ' . $data['creation_date'] . '<br>'
        ));
    }

    /**
     * Helper method that removes all the keys from the store collection used for
     * the multistep form.
     */
    protected function deleteStore()
    {
        $keys = ['first_name', 'last_name', 'gender', 'birthday', 'city', 'phone', 'address', 'user', 'password'];
        foreach ($keys as $key) {
            $this->store->delete($key);
        }
    }

    /**
     * Save data in table "zeoga_user"
     * @param type $data
     * @return type
     */
    protected function setZemogaUser($data)
    {
        $query = \Drupal::database()->insert('zemoga_user');
        $query->fields($data);
        $id = $query->execute();
        return $id;
    }

    /**
     * Create a Drupal user with data from form wizard
     * @param type $data
     * @return type
     */
    protected function createUser($data)
    {
        $user = \Drupal\user\Entity\User::create();
        $user->setPassword($data['password']);
        $user->enforceIsNew();
        $user->setUsername($data['user']);
        $res = $user->save();
        $user_id = $user->id();
        return $user_id;
    }
}
