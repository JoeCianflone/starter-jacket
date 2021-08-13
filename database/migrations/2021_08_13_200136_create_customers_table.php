<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            // $table->enum('status', Arr::flatten(CustomerStatus::toArray()))->default(CustomerStatus::PENDING());
            // $table->enum('payment_type', Arr::flatten(CustomerPaymentType::toArray()))->default(CustomerPaymentType::STRIPE_CARD());
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country')->default('USA');
            $table->boolean('agreed_to_terms')->default(true);
            $table->boolean('agreed_to_privacy')->default(true);
            $table->string('stripe_id')->nullable()->index();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
