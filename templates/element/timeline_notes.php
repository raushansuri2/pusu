<ul class="timeline">
    <?php if (!empty($timelineData)): ?>
        <?php foreach ($timelineData as $timelineItem): ?>
            <li class="timeline-item">
                <div class="timeline-content timeline-type file">
                    <div class="timeline-icon">
                        <i class="link-icon icon-md" data-feather="file-text"></i>
                    </div>
                    <div class="timeline-header">
                        <span class="timeline-author">
                            <?php echo htmlspecialchars($timelineItem->user->firstName ?? 'System') . ' ' . htmlspecialchars($timelineItem->user->lastName ?? 'User'); ?>
                        </span>
                        <p class="timeline-activity mr-1">
                            updated Quote Request #<?php echo htmlspecialchars($RequestQuots->id); ?>
                        </p>
                        <span class="timeline-time">
                            <?php echo $timelineItem->created ? $timelineItem->created->format('M d, Y (g:ia)') : ''; ?>
                        </span>
                        <div class="timeline-summary">
                            <table class="table table-bordered table-sm font-size-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <div class="diff">
                                                <span class="new">
                                                    <span class="new badge badge-secondary">
                                                        <?php
                                                        $statusOptions = \Cake\Core\Configure::read('keyFeatures.STATUS');
                                                        echo isset($statusOptions[$timelineItem->status]) ? $statusOptions[$timelineItem->status] : 'Unknown';
                                                        ?>
                                                    </span>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php if (!empty($timelineItem->message)): ?>
                                    <tr>
                                        <td width="100px">Note</td>
                                        <td><?php echo htmlspecialchars($timelineItem->message); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li class="timeline-item">
            <div class="timeline-content timeline-type file">
                <div class="timeline-icon">
                    <i class="link-icon icon-md" data-feather="info"></i>
                </div>
                <div class="timeline-header">
                    <span class="timeline-author">System</span>
                    <p class="timeline-activity mr-1">No timeline activity available</p>
                    <span class="timeline-time">
                        <?php echo date('M d, Y (g:ia)'); ?>
                    </span>
                    <div class="timeline-summary">
                        <table class="table table-bordered table-sm font-size-sm">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <em>No status updates have been recorded for this quote request yet.</em>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </li>
    <?php endif; ?>
</ul>
