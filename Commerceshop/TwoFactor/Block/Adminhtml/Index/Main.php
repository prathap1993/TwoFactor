<?php

namespace Commerceshop\TwoFactor\Block\Adminhtml\Index;

class Main extends \Magento\User\Block\User\Edit\Tab\Main
{
    
    protected function _prepareForm()
    {
        
        $model = $this->_coreRegistry->registry('permissions_user');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('user_');

        $baseFieldset = $form->addFieldset('base_fieldset', ['legend' => __('Account Information')]);

        if ($model->getUserId()) {
            $baseFieldset->addField('user_id', 'hidden', ['name' => 'user_id']);
        } else {
            if (!$model->hasData('is_active')) {
                $model->setIsActive(1);
            }
        }

        $baseFieldset->addField(
            'username',
            'text',
            [
                'name' => 'username',
                'label' => __('User Name'),
                'id' => 'username',
                'title' => __('User Name'),
                'required' => true
            ]
        );

        $baseFieldset->addField(
            'firstname',
            'text',
            [
                'name' => 'firstname',
                'label' => __('First Name'),
                'id' => 'firstname',
                'title' => __('First Name'),
                'required' => true
            ]
        );

        $baseFieldset->addField(
            'lastname',
            'text',
            [
                'name' => 'lastname',
                'label' => __('Last Name'),
                'id' => 'lastname',
                'title' => __('Last Name'),
                'required' => true
            ]
        );

        $baseFieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'id' => 'customer_email',
                'title' => __('User Email'),
                'class' => 'required-entry validate-email',
                'required' => true
            ]
        );

        $baseFieldset->addField(
                    'phone',
                    'text',
                    [
                        'name' => 'phone',
                        'label' => __('phone'),
                        'id' => 'phone',
                        'title' => __('User phone'),
                        'class' => 'required-entry',
                        'required' => true
                    ]
                );
        $isNewObject = $model->isObjectNew();
        if ($isNewObject) {
            $passwordLabel = __('Password');
        } else {
            $passwordLabel = __('New Password');
        }
        $confirmationLabel = __('Password Confirmation');
        $this->_addPasswordFields($baseFieldset, $passwordLabel, $confirmationLabel, $isNewObject);

        $baseFieldset->addField(
            'interface_locale',
            'select',
            [
                'name' => 'interface_locale',
                'label' => __('Interface Locale'),
                'title' => __('Interface Locale'),
                'values' => $this->_LocaleLists->getTranslatedOptionLocales(),
                'class' => 'select'
            ]
        );

        if ($this->_authSession->getUser()->getId() != $model->getUserId()) {
            $baseFieldset->addField(
                'is_active',
                'select',
                [
                    'name' => 'is_active',
                    'label' => __('This account is'),
                    'id' => 'is_active',
                    'title' => __('Account Status'),
                    'class' => 'input-select',
                    'options' => ['1' => __('Active'), '0' => __('Inactive')]
                ]
            );
        }

        $baseFieldset->addField('user_roles', 'hidden', ['name' => 'user_roles', 'id' => '_user_roles']);

        $currentUserVerificationFieldset = $form->addFieldset(
            'current_user_verification_fieldset',
            ['legend' => __('Current User Identity Verification')]
        );
        $currentUserVerificationFieldset->addField(
            self::CURRENT_USER_PASSWORD_FIELD,
            'password',
            [
                'name' => self::CURRENT_USER_PASSWORD_FIELD,
                'label' => __('Your Password'),
                'id' => self::CURRENT_USER_PASSWORD_FIELD,
                'title' => __('Your Password'),
                'class' => 'input-text validate-current-password required-entry',
                'required' => true
            ]
        );

        $data = $model->getData();
        unset($data['password']);
        unset($data[self::CURRENT_USER_PASSWORD_FIELD]);
        $form->setValues($data);

        $this->setForm($form);

        /*return parent::_prepareForm();*/
    }

    /**
     * Add password input fields
     *
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @param string $passwordLabel
     * @param string $confirmationLabel
     * @param bool $isRequired
     * @return void
     */
    protected function _addPasswordFields(
        \Magento\Framework\Data\Form\Element\Fieldset $fieldset,
        $passwordLabel,
        $confirmationLabel,
        $isRequired = false
    ) {
        $requiredFieldClass = $isRequired ? ' required-entry' : '';
        $fieldset->addField(
            'password',
            'password',
            [
                'name' => 'password',
                'label' => $passwordLabel,
                'id' => 'customer_pass',
                'title' => $passwordLabel,
                'class' => 'input-text validate-admin-password' . $requiredFieldClass,
                'required' => $isRequired
            ]
        );
        $fieldset->addField(
            'confirmation',
            'password',
            [
                'name' => 'password_confirmation',
                'label' => $confirmationLabel,
                'id' => 'confirmation',
                'title' => $confirmationLabel,
                'class' => 'input-text validate-cpassword' . $requiredFieldClass,
                'required' => $isRequired
            ]
        );
    }
}
