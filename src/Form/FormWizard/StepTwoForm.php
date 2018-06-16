<?php
/**
 * @file
 * Contains \Drupal\zemoga_form_wizard\Form\FormWizard\StepTwoForm.
 */
namespace Drupal\zemoga_form_wizard\Form\FormWizard;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class StepTwoForm extends FormWizard
{

    /**
     * {@inheritdoc}.
     */
    public function getFormId()
    {
        return 'multistep_form_two';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form = parent::buildForm($form, $form_state);

        $form['city'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('City'),
            '#default_value' => $this->store->get('city') ? $this->store->get('city') : '',
            '#required' => true,
        );

        $form['phone'] = array(
            '#type' => 'tel',
            '#title' => $this->t('Phone number'),
            '#default_value' => $this->store->get('phone') ? $this->store->get('phone') : '',
        );
        
        $form['address'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Address'),
            '#default_value' => $this->store->get('address') ? $this->store->get('address') : '',
        );

        $form['actions']['previous'] = array(
            '#type' => 'link',
            '#title' => $this->t('Previous'),
            '#attributes' => array(
                'class' => array('button'),
            ),
            '#weight' => 0,
            '#url' => Url::fromRoute('zemoga.step_one'),
        );
        $form['actions']['submit']['#value'] = $this->t('Next');

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('city') === '') {
            $form_state->setErrorByName('city', $this->t('City is mandatory'));
        }
        parent::validateForm($form, $form_state);
    }
    
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->store->set('city', $form_state->getValue('city'));
        $this->store->set('phone', $form_state->getValue('phone'));
        $this->store->set('address', $form_state->getValue('address'));
        // Redirect to next step
        $form_state->setRedirect('zemoga.step_three');
    }
}
