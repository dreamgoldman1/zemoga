<?php
/**
 * @file
 * Contains \Drupal\zemoga_form_wizard\Form\FormWizard\StepOneForm.
 */
namespace Drupal\zemoga_form_wizard\Form\FormWizard;

use Drupal\Core\Form\FormStateInterface;

class StepOneForm extends FormWizard
{

    /**
     * {@inheritdoc}.
     */
    public function getFormId()
    {
        return 'multistep_form_one';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form = parent::buildForm($form, $form_state);

        $form['first_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('First name'),
            '#default_value' => $this->store->get('first_name') ? $this->store->get('first_name') : '',
            '#required' => true,
        );

        $form['last_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Last name'),
            '#default_value' => $this->store->get('last_name') ? $this->store->get('last_name') : '',
            '#required' => true,
        );

        $form['gender'] = array(
            '#type' => 'select',
            '#title' => $this->t('Gender'),
            '#options' => array(
                '' => 'Select one',
                'Male' => 'Male',
                'Female' => 'Femail',
            ),
            '#default_value' => $this->store->get('gender') ? $this->store->get('gender') : '',
            '#required' => true,
        );

        $form['birthday'] = array(
            '#type' => 'date',
            '#title' => $this->t('Birthday'),
            '#default_value' => $this->store->get('birthday') ? $this->store->get('birthday') : '',
            '#required' => true,
        );

        $form['actions']['submit']['#value'] = $this->t('Next');
        //$form['actions']['submit']['#ajax']['callback'] = '::ajaxCallback';
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('first_name') === '') {
            $form_state->setErrorByName('first_name', $this->t('First Name is mandatory'));
        }
        if ($form_state->getValue('last_name') === '') {
            $form_state->setErrorByName('last_name', $this->t('Last Name is mandatory'));
        }
        if ($form_state->getValue('gender') === '') {
            $form_state->setErrorByName('gender', $this->t('Gender is mandatory'));
        }
        if ($form_state->getValue('birthday') === '') {
            $form_state->setErrorByName('birthday', $this->t('Birthday is mandatory'));
        }
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->store->set('first_name', $form_state->getValue('first_name'));
        $this->store->set('last_name', $form_state->getValue('last_name'));
        $this->store->set('gender', $form_state->getValue('gender'));
        $this->store->set('birthday', $form_state->getValue('birthday'));
        // Redirect to next step
        $form_state->setRedirect('zemoga.step_two');
    }

    public function ajaxCallback(array &$form, FormStateInterface $form_state)
    {
        return $form;
    }
}
