<style>
    .appint-details {
        padding: 14px 0;
        border-bottom: 1px solid #E4E4E4;
    }
    .appint-details label {
        float: right;
    }
    .avatar-box {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 50%;
        margin-left: 295px;
        background-color: #f0f0f0;
    }
    .edit-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Uncommented for proper image scaling */
    }
</style>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <?= $this->Flash->render() ?>
        <div class="add-listing-box edit-info mrg-bot-25 padd-30" style="padding:30px">
            <div class="row">
                <?php if ($this->request->getSession()->read('RitevetUsers.id') == $request->sender_user->id) : ?>
                    <div class="col-md-12">
                        <div class="listing-box-header">
                            <div class="avatar-box">
                                <?php
                                $UIMG = !empty($request->service_provider_user->profile_picture)
                                    ? $this->Url->build('/img/uploads/users/' . $request->service_provider_user->profile_picture)
                                    : $this->Url->build('/img/dummy.jpg');
                                ?>
                                <img src="<?= $UIMG ?>" class="img-responsive img-circle edit-avatar" alt="profile picture">
                            </div>
                            <h3 style="font-size: 22px; font-weight: bold; margin-top: 10px;">
                                <?= h($request->service_provider_user->firstName) ?> <?= h($request->service_provider_user->lastName) ?>
                            </h3>
                            <p>
                                <?php
                                $userType = $request->service_provider_user->usersinformation->UTYPE ?? null;
                                echo match ($userType) {
                                    1 => 'Pet Parent',
                                    2 => 'Veterinarian',
                                    default => 'Pet Service Provider',
                                };
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row mrg-r-10 mrg-l-10 preview-info">
                            <div class="col-sm-6 text-left">
                                <label><i class="ti-location-pin preview-icon birth mrg-r-10"></i><?= h($request->sender_user->address ?? '') ?></label>
                            </div>
                            <!--CHAT-->
                            <?php if ($chat) : ?>
                                <div class="col-sm-6 text-left">
                                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'chats', $request->id]) ?>" target="_blank" style="cursor: pointer;">
                                        <label><i class="ti-comments preview-icon birth mrg-r-10"></i>Chat</label>
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="col-sm-6 text-left">
                                    <a data-toggle="modal" data-target="#chatModal" style="cursor: pointer;">
                                        <label><i class="ti-comments preview-icon birth mrg-r-10"></i>Chat</label>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <!--AUDIO CALL-->
                            <?php if ($request->UTYPE == 2 && $request->bookingbusines->id == 3 && ($request->service_provider_user->usersinformation->AudioChat ?? 0) == 2) : ?>
                                <div class="col-sm-6 text-left">
                                    <a href="#" data-toggle="modal" data-target="#exampleModal" style="cursor: pointer;">
                                        <label><i class="fa fa-file-audio-o preview-icon email mrg-r-10"></i>Audio Call</label>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <!--VIDEO CALL-->
                            <?php if (
                                ($request->UTYPE == 2 && $request->bookingbusines->id == 3 && ($request->service_provider_user->usersinformation->videoChat ?? 0) == 2) ||
                                ($request->UTYPE == 3 && $request->bookingservice->id == 16 && ($request->service_provider_user->usersinformation->videoChat ?? 0) == 2)
                            ) : ?>
                                <div class="col-sm-6 text-left">
                                    <a href="#" data-toggle="modal" data-target="#exampleModal1" style="cursor: pointer;">
                                        <label><i class="fa fa-file-video-o preview-icon birth mrg-r-10"></i>Video Call</label>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php
                        $isFutureDate = fn($date) => $date >= strtotime(date('Y-m-d'));
                        $hasFutureDate = $request->requested_service_date
                            ? $isFutureDate(strtotime($request->requested_service_date))
                            : (!empty($request->multi_date) && array_filter(array_map('strtotime', explode(',', $request->multi_date)), $isFutureDate));
                        $hasFutureOrNextDay = $request->requested_service_date
                            ? strtotime('+1 day', strtotime($request->requested_service_date)) >= strtotime(date('Y-m-d'))
                            : (!empty($request->multi_date) && array_filter(array_map('strtotime', explode(',', $request->multi_date)), $isFutureDate));
                        ?>
                        <?php if (in_array($request->status, [0, 1, 4]) && $hasFutureDate) : ?>
                            <div class="col-sm-6 text-left">
                                <a style="color: #004eff;" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'cancelsentappointments', $request->id]) ?>" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                    <span class="btn btn-primary btn-sm">Cancel</span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array($request->status, [4, 5]) && $hasFutureOrNextDay) : ?>
                            <div class="col-sm-6 text-left">
                                <a style="color: #004eff;" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'noShow', $request->id]) ?>" onclick="return confirm('Are you sure you want to mark this request as No Show?');">
                                    <span class="btn btn-primary btn-sm" title="mean that service provider didn’t show">No Show?</span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <!--PAYMENT URL-->
                        <div class="col-sm-6 text-left">
                            <?php
                            $requestDate = !empty($request->requested_service_date)
                                ? $request->requested_service_date
                                : (!empty($request->multi_date) ? trim(explode(',', $request->multi_date)[0]) : null);
                            if ($request->status == 1 && $requestDate && strtotime($requestDate) >= strtotime(date('Y-m-d'))) {
                                $urlParams = [
                                    'controller' => 'Users',
                                    'action' => 'payment',
                                    base64_encode($request->service_provider_user->id),
                                    base64_encode($request->sender_user->id),
                                    base64_encode($request->UTYPE),
                                ];

                                if ($request->UTYPE == 3) {
                                    if ($request->bookingservice->id == 16) {
                                        $urlParams = array_merge($urlParams, [
                                            base64_encode($request->bookingservice->id),
                                            base64_encode($request->bookingservice->name),
                                            base64_encode($request->videochatavailability->fees),
                                            base64_encode($request->videochatavailability->price),
                                            base64_encode($request->videochatavailability->total_price),
                                        ]);
                                    } else {
                                        $multiDates = !empty($request->multi_date) ? explode(',', $request->multi_date) : [];
                                        $rateMultiplier = count($multiDates) ?: 1;
                                        $rate = $request->servicerate->rate * $rateMultiplier;
                                        $finalRate = ($rate * $request->servicerate->tax) + $rate;
                                        $urlParams = array_merge($urlParams, [
                                            base64_encode($request->bookingservice->id),
                                            base64_encode($request->bookingservice->name),
                                            base64_encode($request->servicerate->tax),
                                            base64_encode($rate),
                                            base64_encode($finalRate),
                                        ]);
                                    }
                                } elseif ($request->UTYPE == 2) {
                                    if ($request->bookingbusines->id == 3) {
                                        $urlParams = array_merge($urlParams, [
                                            base64_encode($request->bookingbusines->id),
                                            base64_encode($request->bookingbusines->name),
                                            base64_encode($request->videochatavailability->fees),
                                            base64_encode($request->videochatavailability->price),
                                            base64_encode($request->videochatavailability->total_price),
                                        ]);
                                    } else {
                                        $urlParams = array_merge($urlParams, [
                                            base64_encode($request->bookingbusines->id),
                                            base64_encode($request->bookingbusines->name),
                                            base64_encode($request->mobileavailability->fees),
                                            base64_encode($request->mobileavailability->mobileprice),
                                            base64_encode($request->mobileavailability->total_price),
                                        ]);
                                    }
                                }
                                $urlParams[] = base64_encode($requestDate);
                                $url = $this->Url->build($urlParams);
                            ?>
                                <a style="color: #004eff;" href="<?= $url ?>" target="_blank">
                                    <span class="btn btn-primary btn-sm text-center">Pay</span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-md-12"><hr style="display: block; clear: both;"></div>
                <div class="col-md-12">
                    <div class="appint-details">
                        <i class="fa fa-list-ul"></i> <?= $request->UTYPE == 3 ? 'Booked Service Name:' : 'Booked Business Name:' ?> 
                        <label><?= h($request->UTYPE == 3 ? $request->bookingservice->name : $request->bookingbusines->name) ?></label>
                    </div>
                    <div class="appint-details">
                        <i class="fa fa-calendar"></i> Appointment Date: 
                        <label>
                            <?php
                            if (!empty($request->requested_service_date)) {
                                echo h(date("F jS, Y", strtotime($request->requested_service_date)));
                            } elseif (!empty($request->multi_date)) {
                                $dates = explode(',', $request->multi_date);
                                $formattedDates = array_map(fn($date) => date("F jS, Y", strtotime(trim($date))), $dates);
                                echo h(implode(' , ', $formattedDates));
                            }
                            ?>
                        </label>
                    </div>
                    <?php if (!empty($request->time_slot_UTC)) : ?>
                        <div class="appint-details">
                            <i class="fa fa-clock-o"></i> Appointment Time: 
                            <label>
                                <?php
                                try {
                                    [$startTime, $endTime] = explode(' - ', $request->time_slot_UTC);
                                    $timezone = $this->request->getSession()->read('Config.timezone') ?? 'UTC';
                                    $startDateTime = \DateTime::createFromFormat('g:i A', trim($startTime), new \DateTimeZone('UTC'));
                                    $endDateTime = \DateTime::createFromFormat('g:i A', trim($endTime), new \DateTimeZone('UTC'));
                                    if ($startDateTime && $endDateTime) {
                                        $start = (new \Cake\I18n\FrozenTime($startDateTime))->setTimezone($timezone);
                                        $end = (new \Cake\I18n\FrozenTime($endDateTime))->setTimezone($timezone);
                                        echo h($start->format('g:i A') . ' - ' . $end->format('g:i A'));
                                    } else {
                                        echo h('Invalid time slot');
                                    }
                                } catch (\Exception $e) {
                                    echo h('Invalid time slot');
                                }
                                ?>
                            </label>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($request->prefere_times)) : ?>
                        <div class="appint-details">
                            <i class="fa fa-clock-o"></i> Preferred times for the visit: 
                            <label><?= h($request->prefere_times) ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($request->sender_address) && !empty($request->sender_zipcode)) : ?>
                        <div class="appint-details">
                            <i class="fa fa-map-marker"></i> Appointment Address: 
                            <label><?= h($request->sender_address) ?>, <?= h($request->sender_zipcode) ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($request->typeofpet)) : ?>
                        <div class="appint-details">
                            <i class="fa fa-paw"></i> Pet Type: 
                            <label><?= h($request->typeofpet->name) ?></label>
                        </div>
                        <?php if ($request->typeofpet->id == 1) : ?>
                            <div class="appint-details">
                                <i class="fa fa-paw"></i> Dog Size: 
                                <label><?= h($request->dog_size ?? '') ?></label>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!empty($request->comment)) : ?>
                        <div class="appint-details">
                            <i class="fa fa-comment"></i> Comment: 
                            <label><?= nl2br(h($request->comment)) ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($request->created)) : ?>
                        <div class="appint-details">
                            <i class="fa fa-calendar"></i> Appointment Created: 
                            <label><?= h(date('F j, Y, g:i a', strtotime($request->created))) ?></label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<!-- Audio Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Audio Call</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>For Audio Consultation with Veterinarian or Other Pet Provider you need to download our Ritevet App. To get the app, search Ritevet on the Play Store if you are an android user and if you are a Apple user search RiteVet on Apple Store.</p>
                <p>Download the app and find best veterinarians and other pet services instantly</p>
                <p><a href="https://apps.apple.com/app/ritevet/id1672966112" target="_blank"><img src="http://ritevet.com/assets/img/app.png" alt="App Store"></a></p>
            </div>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Video Call</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>For Video Consultation with Veterinarian or Other Pet Provider you need to download our Ritevet App. To get the app, search Ritevet on the Play Store if you are an android user and if you are a Apple user search RiteVet on Apple Store.</p>
                <p>Download the app and find best veterinarians and other pet services instantly</p>
                <p><a href="https://apps.apple.com/app/ritevet/id1672966112" target="_blank"><img src="http://ritevet.com/assets/img/app.png" alt="App Store"></a></p>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Chat Not Available</h4>
            </div>
            <div class="modal-body">
                <p>Sorry, chat is not available for you until you complete the payment for this booking.</p>
                <p>Please make the payment to unlock the chat feature.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>