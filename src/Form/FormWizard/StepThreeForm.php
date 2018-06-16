<?php
/**
 * @file
 * Contains \Drupal\zemoga_form_wizard\Form\FormWizard\StepThreeForm.
 */
namespace Drupal\zemoga_form_wizard\Form\FormWizard;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class StepThreeForm extends FormWizard
{

    /**
     * {@inheritdoc}.
     */
    public function getFormId()
    {
        return 'multistep_form_three';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form = parent::buildForm($form, $form_state);

        $form['user'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('User Id'),
            '#default_value' => $this->store->get('user') ? $this->store->get('user') : '',
            '#required' => true,
        );

        $form['password'] = array(
            '#type' => 'password',
            '#title' => $this->t('Password'),
            '#default_value' => $this->store->get('password') ? $this->store->get('password') : '',
            '#required' => true,
        );
        
        $form['actions']['previous'] = array(
            '#type' => 'link',
            '#title' => $this->t('Previous'),
            '#attributes' => array(
                'class' => array('button'),
            ),
            '#weight' => 0,
            '#url' => Url::fromRoute('zemoga.step_two'),
        );

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('user') === '') {
            $form_state->setErrorByName('user', $this->t('User is mandatory'));
        }
        if ($form_state->getValue('password') === '') {
            $form_state->setErrorByName('password', $this->t('Password is mandatory'));
        }
        parent::validateForm($form, $form_state);
    }
    
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->store->set('user', $form_state->getValue('user'));
        $this->store->set('password', $form_state->getValue('password'));

        // Save the data
        parent::saveData();
        
        // Redirect to first step
        $form_state->setRedirect('zemoga.step_one');
    }
}
