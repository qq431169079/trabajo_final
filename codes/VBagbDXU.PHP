<?php
class ControllerProductFastorder extends Controller {
    // Set the developer version
    private static $dev = false;

    public function index($data) {
        // Debugging
        if(self::$dev){
            // Write message if developer version is enable.
            echo '<div style="margin:0px; width:100%; text-align: right; padding:0 10px; font-weight:bold; color:#c53d36">This is developer version (FastOrder)!</div>';
        }

        // If page not found - do not show button
        if(empty($data['name'])){
            return false;
        }

        // If Display stock is enable and out of stock is disbale - do not show fastorder button
        if ( $this->config->get('config_stock_display')
                and !$this->config->get('config_stock_checkout')
                and $this->model_catalog_product->getProduct($data['product_id'])['quantity'] < 1
            ) {
            return false;
        }

        // Load lanuage
        $this->load->language('product/fastorder');

        // Language data
        $data['text_fastorder_button']                      = $this->language->get('text_fastorder_button');
        $data['text_fastorder_form_header']                 = $this->language->get('text_fastorder_form_header');
        $data['text_fastorder_form_info']                   = $this->language->get('text_fastorder_form_info');
        $data['text_fastorder_name']                        = $this->language->get('text_fastorder_name');
        $data['text_fastorder_phone']                       = $this->language->get('text_fastorder_phone');
        $data['text_fastorder_mail']                        = $this->language->get('text_fastorder_mail');
        $data['text_fastorder_comment']                     = $this->language->get('text_fastorder_comment');
        $data['text_fastorder_button_submit']               = $this->language->get('text_fastorder_button_submit');
        $data['text_fastorder_button_close']                = $this->language->get('text_fastorder_button_close');
        $data['text_fastorder_success_message']             = $this->language->get('text_fastorder_success_message');
        $data['text_fastorder_button_cancel']               = $this->language->get('text_fastorder_button_cancel');
        $data['text_fastorder_input_name_placeholder']      = $this->language->get('text_fastorder_input_name_placeholder');
        $data['text_fastorder_input_phone_placeholder']     = $this->language->get('text_fastorder_input_phone_placeholder');
        $data['text_fastorder_input_mail_placeholder']      = $this->language->get('text_fastorder_input_mail_placeholder');
        $data['text_fastorder_input_comment_placeholder']   = $this->language->get('text_fastorder_input_comment_placeholder');
        $data['text_fastorder_success_title']               = $this->language->get('text_fastorder_success_title');
        $data['text_fastorder_mail_msg_order']              = $this->language->get('text_fastorder_mail_msg_order');
        $data['text_fastorder_mail_msg_price']              = $this->language->get('text_fastorder_mail_msg_price');
        $data['txt_text_fastorder_form_info_message']       = $this->language->get('txt_text_fastorder_form_info_message');
        $data['txt_none_price']                             = $this->language->get('txt_none_price');

        // If special price is set - display it
        if(isset($data['special'])){
          $data['price'] = $data['special'];
        }else{

        }

        if(!isset($data['price'])){
          $data['price'] = $data['txt_none_price'];
        }

        if($this->config->get('config_template')) {
            $template = $this->config->get('config_template');
        }else{
            $template = 'default';
        }

        // Need before rewrite module (hack)
        $data['product_name'] = $data['name'];

        // Get the product link
        // if (isset($this->request->server['REQUEST_SCHEME'])) {
        //     $data['product_link'] = $this->request->server['REQUEST_SCHEME'].'://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
        // }else{
        //     $data['product_link'] = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
        // }

        // Get the product link
        if (isset($this->request->get['path'])) {
           $path = 'path=' . $this->request->get['path'];
        }else{
            $path = 'route=' . $this->request->get['route'];
        }

        $data['product_link'] = $this->url->link('product/product', $path . '&product_id=' . $data['product_id']);

        // FastOrder price var
        $data['price'] = $this->currency->format($this->tax->calculate($data['price'], $data['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'],'',false);

        // FastOrder special price var
        if($data['special']){
            $fo_special = $this->currency->format($this->tax->calculate($data['special'], $data['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'],'',false);
        }else{
            $fo_special = null;
        }


        $this->document->addStyle('catalog/view/theme/'. $template.'/stylesheet/fastorder.css');

        if(VERSION >= '2.2.0.0') {
          return $this->load->view('product/fastorder', $data);
        }else{
          return $this->load->view($this->config->get('config_template') . '/template/product/fastorder.tpl', $data);
        }
    }

    public function getForm(){
        // Get the currency symbol
        $data['symbolLeft'] = $this->currency->getSymbolLeft($this->session->data['currency']) ? $this->currency->getSymbolLeft($this->session->data['currency']) : '';
        $data['symbolRight'] = $this->currency->getSymbolRight($this->session->data['currency']);

        // Load lanuage
        $this->load->language('product/fastorder');

// =================================================================================================
//Getting the customer data

        // Get Customer info (if user login in system)
        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

        if($customer_info){
            $data['customer_id'] = $this->customer->getId();
            $data['customer_group_id'] = $customer_info['customer_group_id'];
            $data['username'] = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
            $data['email'] = $customer_info['email'];
            $data['telephone'] = $customer_info['telephone'];
        }
// =================================================================================================

        // Language data
        $data['text_fastorder_button']                      = $this->language->get('text_fastorder_button');
        $data['text_fastorder_form_header']                 = $this->language->get('text_fastorder_form_header');
        $data['text_fastorder_form_info']                   = $this->language->get('text_fastorder_form_info');
        $data['text_fastorder_name']                        = $this->language->get('text_fastorder_name');
        $data['text_fastorder_phone']                       = $this->language->get('text_fastorder_phone');
        $data['text_fastorder_comment']                     = $this->language->get('text_fastorder_comment');
        $data['text_fastorder_button_submit']               = $this->language->get('text_fastorder_button_submit');
        $data['text_fastorder_button_close']                = $this->language->get('text_fastorder_button_close');
        $data['text_fastorder_success_message']             = $this->language->get('text_fastorder_success_message');
        $data['text_fastorder_button_cancel']               = $this->language->get('text_fastorder_button_cancel');
        $data['text_fastorder_input_name_placeholder']      = $this->language->get('text_fastorder_input_name_placeholder');
        $data['text_fastorder_input_phone_placeholder']     = $this->language->get('text_fastorder_input_phone_placeholder');
        $data['text_fastorder_input_mail_placeholder']      = $this->language->get('text_fastorder_input_mail_placeholder');
        $data['text_fastorder_input_comment_placeholder']   = $this->language->get('text_fastorder_input_comment_placeholder');
        $data['text_fastorder_success_title']               = $this->language->get('text_fastorder_success_title');
        $data['text_fastorder_mail_msg_order']              = $this->language->get('text_fastorder_mail_msg_order');
        $data['text_fastorder_mail_msg_price']              = $this->language->get('text_fastorder_mail_msg_price');
        $data['txt_text_fastorder_form_info_message']       = $this->language->get('txt_text_fastorder_form_info_message');
        $data['text_fastorder_count']                       = $this->language->get('text_fastorder_count');
        $data['text_fastorder_input_count_placeholder']     = $this->language->get('text_fastorder_input_count_placeholder');

        $data['product_name'] = $this->request->post['product_name'];
        $data['price'] = $this->request->post['price'];
        $data['product_id'] = $this->request->post['product_id'];

        $data['product_link'] = $this->request->post['product_link'];

        if (VERSION >= '2.2.0.0') {
          $tpl =  $this->load->view('product/fastorder_form', $data);
        }else{
          $tpl =  $this->load->view($this->config->get('config_template') . '/template/product/fastorder_form.tpl', $data);
        }

        $this->response->setOutput($tpl);
    }

    public function sender(){
        // Load language
        $this->load->language('product/fastorder');

        // Language data
        $data['text_fastorder_mail_subject']    = $this->language->get('text_fastorder_mail_subject');
        $data['text_fastorder_mail_msg_data']   = $this->language->get('text_fastorder_mail_msg_data');
        $data['text_fastorder_name']            = $this->language->get('text_fastorder_name');
        $data['text_fastorder_phone']           = $this->language->get('text_fastorder_phone');
        $data['text_fastorder_mail']            = $this->language->get('text_fastorder_mail');
        $data['text_fastorder_comment']         = $this->language->get('text_fastorder_comment');
        $data['text_fastorder_mail_msg_order']  = $this->language->get('text_fastorder_mail_msg_order');
        $data['text_fastorder_mail_msg_price']  = $this->language->get('text_fastorder_mail_msg_price');
        $data['text_fastorder_mail_msg_count']  = $this->language->get('text_fastorder_mail_msg_count');
        $data['text_fastorder_mail_msg_total']  = $this->language->get('text_fastorder_mail_msg_total');

        // Get the currency symbol
        $data['symbolLeft'] = $this->currency->getSymbolLeft($this->session->data['currency']) ? $this->currency->getSymbolLeft($this->session->data['currency']) : '';
        $data['symbolRight'] = $this->currency->getSymbolRight($this->session->data['currency']);

        $json = array();

        if (isset($this->request->post['product_id'])){
          $json['product_id'] = $this->request->post['product_id'];
        }
        if (isset($this->request->post['name'])){
          $json['name'] = $this->request->post['name'];
        }
        if (isset($this->request->post['phone'])){
          $json['phone'] = $this->request->post['phone'];
        }
        if (isset($this->request->post['mail']) and !empty($this->request->post['mail'])){
          $json['mail'] = $this->request->post['mail'];
        }else{
             $json['mail'] = ' ';
        }
        if (isset($this->request->post['comment'])){
          $json['comment'] = $this->request->post['comment'];
        }
        if (isset($this->request->post['product_name'])){
          $json['product_name'] = $this->request->post['product_name'];
        }
        if (isset($this->request->post['price'])){
          $json['price'] = $this->request->post['price'];
        }
        if (isset($this->request->post['count']) and !empty($this->request->post['count']) and
            $this->request->post['count'] != 0){
          $json['count'] = $this->request->post['count'];
        }else{
            $json['count'] = 1;
        }
        if (isset($this->request->post['total'])){
            $json['total'] = $this->request->post['total'];
        }

        // Need to test new features
        if (self::$dev) {
            require_once 'developer_test_file.php';
        }


        // Mail subject
        $subject    = $data['text_fastorder_mail_subject'] .' ('.$_SERVER['HTTP_HOST'] . ')';
        $products   = $json['product_name'];

        // Data to mail template
        $mail_tmpl_data = array (
            'product_link'                  => $this->request->post['product_link'],
            'subject'                       => $subject,
            'text_fastorder_name'           => $data['text_fastorder_name'],
            'text_fastorder_mail_msg_data'  => $data['text_fastorder_mail_msg_data'],
            'name'                          => $json['name'],
            'text_fastorder_phone'          => $data['text_fastorder_phone'],
            'phone'                         => $json['phone'],
            'text_fastorder_mail'           => $data['text_fastorder_mail'],
            'mail'                          => $json['mail'],
            'text_fastorder_comment'        => $data['text_fastorder_comment'],
            'comment'                       => $json['comment'],
            'text_fastorder_mail_msg_order' => $data['text_fastorder_mail_msg_order'],
            'text_fastorder_mail_msg_price' => $data['text_fastorder_mail_msg_price'],
            'price'                         => $json['price'],
            'count'                         => $json['count'],
            'text_fastorder_mail_msg_count' => $data['text_fastorder_mail_msg_count'],
            'total'                         => $json['total'],
            'text_fastorder_mail_msg_total' => $data['text_fastorder_mail_msg_total'],
            'config_name'                   => $this->config->get('config_name'),
            'config_telephone'              => $this->config->get('config_telephone'),
            'config_email'                  => $this->config->get('config_email'),
            'products'                      => $products,
            'symbolLeft'                    => $data['symbolLeft'],
            'symbolRight'                   => $data['symbolRight']
            );

        // Get the main message template
        if (VERSION >= '2.2.0.0') {
          $mail_message =  $this->load->view('mail/fastorder_mail_msg', $mail_tmpl_data);
        }else{
          $mail_message =  $this->load->view($this->config->get('config_template') . '/template/mail/fastorder_mail_msg.tpl', $mail_tmpl_data);
        }

        // Debugging
        if(self::$dev){
            // Write mail messege to the file.
            file_put_contents('./mail_message.html', $mail_message);
        }

        $email_to = $this->config->get('config_email');

        // Create OpenCart mail object
        $mail = new Mail();

        // Гребанные мудаки, разработчики Opencart, никак не могу определиться с названием параметров конфига. Ебланы хуевы, как можно быть такими... ну блин... это же будут читать тысячи пользователей... Я адски зол, где стандарты, мать его....
        // Пока что тестировано на OC_2.2, и каком-то более низком через mail, без использования SMTP
        // Потребуется доработка под разные версии.
        // Последнее изменение с версии 1.2.1

        // SMTP mail in tested mode.
        // Now working with mail is normal.
        if (VERSION >= '2.2.0.0') {
            // Setup the mail parameters
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = $this->config->get('config_mail_smtp_password');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
            $mail->setTo($email_to);
            $mail->setFrom(explode(',', $this->config->get('config_email'))[0]);
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($data['text_fastorder_mail_subject'] .' ('.$_SERVER['HTTP_HOST'] . ')'), ENT_QUOTES, 'UTF-8');
            $mail->setHtml($mail_message);
            $mail->setText(html_entity_decode($mail_message, ENT_QUOTES, 'UTF-8'));
        }else{
            // Older
            // Setup the mail parameters
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($email_to);
            $mail->setFrom(explode(',', $this->config->get('config_email'))[0]);
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($data['text_fastorder_mail_subject'] .' ('.$_SERVER['HTTP_HOST'] . ')'), ENT_QUOTES, 'UTF-8');
            $mail->setHtml($mail_message);
            $mail->setText(html_entity_decode($mail_message, ENT_QUOTES, 'UTF-8'));
        }

        // Send mail to the shop owner
        $mail->send();

        //Send to other mail from Config Mail. OC =>2.2.0.0
        if ($this->config->get('config_mail_alert_email')) {
            $mail->setTo($this->config->get('config_mail_alert_email'));
            $mail->send();
        }

        //Send to other mail from Config Mail.OC <2.2.0.0
        if ($this->config->get('config_mail_alert')) {
            $mail->setTo($this->config->get('config_mail_alert'));
            $mail->send();
        }


        // Send mail to the customer
        if(!empty($this->request->post['mail'])){

            $mail->setTo($json['mail']);
            $mail->send();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}