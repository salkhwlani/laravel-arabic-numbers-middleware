<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Test;


class EasternMiddlewareTest extends TestCase
{

    /** @test */
    public function it_will_transform_numbers_to_arabic_with_auto_append_middleware()
    {
        $this->post('login-eastern-to-arabic-auto-append', $this->easternTestData)->assertJson($this->arabicTestData);
    }

    /** @test */
    public function it_will_transform_numbers_to_arabic_with_manual_append_middleware()
    {
        $this->post('login-eastern-to-arabic', $this->easternTestData)->assertJson($this->arabicTestData);
    }

    /** @test */
    public function it_will_skip_transform_numbers_to_arabic_with_ignore_fields_from_config()
    {
        $this->app['config']->set('arabic-numbers-middleware.except_from_eastern_to_arabic', ['login']);
        $this->post('login-eastern-to-arabic-auto-append', $this->easternTestData)->assertJson($this->ignoreTestData);
    }

    /** @test */
    public function it_will_skip_transform_numbers_to_arabic_with_ignore_field_as_global()
    {
        $this->app['config']->set('arabic-numbers-middleware.except_from_all', ['login']);
        $this->post('login-eastern-to-arabic-auto-append', $this->easternTestData)->assertJson($this->ignoreTestData);
    }

    /** @test */
    public function it_will_skip_transform_numbers_to_arabic_with_ignore_fields_from_inline()
    {
        $this->auto_register_middleware = false;
        $this->refreshApp();
        $this->post('login-eastern-to-arabic-ignore-pass-field-inline', $this->easternTestData)->assertJson(['login' => '0500484350', 'pass' => '١٢٣٤٥٦']);
    }

    /** @test */
    public function it_will_not_transform_numbers_to_arabic_without_auto_append_middleware()
    {
        $this->auto_register_middleware = false;
        $this->refreshApp();
        $this->post('login-eastern-to-arabic-auto-append', $this->easternTestData)->assertExactJson($this->easternTestData);
    }
}