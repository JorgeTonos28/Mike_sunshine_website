<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 16)->default('editor')->index();
            $table->boolean('is_active')->default(true);
            $table->string('google_id')->nullable()->unique();
        });
        Schema::create('studio_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Mike Sunshine');
            $table->string('tagline')->nullable();
            $table->text('bio')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('phone', 60)->nullable();
            $table->text('address')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('currency', 3)->default('DOP');
            $table->string('hero_title')->default('Mike Sunshine');
            $table->text('hero_description')->nullable();
            $table->string('hero_path')->nullable();
            $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('kind', 16)->index();
            $table->string('category', 80)->nullable()->index();
            $table->text('summary')->nullable();
            $table->string('technique')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('edition')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedBigInteger('price_minor')->nullable();
            $table->string('currency', 3)->default('DOP');
            $table->unsignedInteger('stock')->default(1);
            $table->string('availability', 20)->default('inquiry');
            $table->boolean('is_published')->default(false)->index();
            $table->boolean('is_featured')->default(false);
            $table->json('content_blocks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt');
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
        Schema::create('legal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->json('content_blocks');
            $table->boolean('is_approved')->default(false);
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('submission_hash', 64)->unique();
            $table->string('status', 20)->default('received')->index();
            $table->text('customer_name');
            $table->text('customer_email');
            $table->text('customer_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('message')->nullable();
            $table->string('delivery_method', 16);
            $table->unsignedBigInteger('subtotal_minor')->nullable();
            $table->string('currency', 3);
            $table->timestamp('privacy_accepted_at');
            $table->unsignedInteger('terms_version')->default(1);
            $table->text('admin_notes')->nullable();
            $table->string('notification_status', 16)->default('pending');
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_price_minor')->nullable();
            $table->string('currency', 3);
            $table->timestamps();
        });
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('email');
            $table->string('subject', 80);
            $table->text('message');
            $table->timestamp('privacy_accepted_at');
            $table->timestamp('read_at')->nullable();
            $table->string('notification_status', 16)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['inquiries', 'order_items', 'orders', 'legal_pages', 'product_images', 'products', 'studio_settings'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['role', 'is_active', 'google_id']));
    }
};
