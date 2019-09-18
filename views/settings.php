<div class="wrap">
    <h2><?php echo $this->_pageTitle;?></h2>
    <div class="item-before-prod-block">

        <div id="list-before-prod" class="hide">
            <ul>
                <?php foreach ($this->methods as $action => $class): ?>
                    <li class="item-action">
                        <?php $rc = new ReflectionClass($class);
                        $doc = $rc->getMethod($action)->getDocComment();
                        if(!empty($doc) && preg_match('#@description#', $doc)){
                            $description = preg_replace('#\/\*\*\n (.*)@description:(.*)#', "$2", $rc->getMethod($action)->getDocComment());
                            $description = rtrim($description, ' */');
                        }
                        ?>
                        <span><?php echo $action; ?> <?php echo !empty($description) ? "<i>(" . $description . ")</i>" : ""; ?></span>
                        <button data-action="<?php echo $action; ?>" class="button-primary __loadAction"><?php echo __('Launch', 'exec-scripts'); ?></button>

                        <svg width='24px' height='24px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring">
                            <rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
                            <circle cx="50" cy="50" r="40" stroke-dasharray="163.36281798666926 87.9645943005142" stroke="#53acd2" fill="none" stroke-width="20">
                                <animateTransform attributeName="transform" type="rotate" values="0 50 50;180 50 50;360 50 50;" keyTimes="0;0.5;1" dur="1s" repeatCount="indefinite" begin="0s"></animateTransform>
                            </circle>
                        </svg>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div>