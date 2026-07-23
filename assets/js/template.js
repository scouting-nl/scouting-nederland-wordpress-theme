(function () {
  'use strict';

  var desktopQuery = window.matchMedia('(min-width: 1181px)');

  function directSubmenu(item) {
    for (var i = 0; i < item.children.length; i += 1) {
      var child = item.children[i];
      if (child.classList.contains('dropdown-menu') || child.classList.contains('sub-menu')) {
        return child;
      }
    }
    return null;
  }

  function positionSubmenu(item) {
    if (!desktopQuery.matches) {
      item.classList.remove('tc4-submenu-open-left');
      return;
    }

    var submenu = directSubmenu(item);
    if (!submenu) {
      return;
    }

    var previousDisplay = submenu.style.display;
    var previousVisibility = submenu.style.visibility;
    submenu.style.display = 'block';
    submenu.style.visibility = 'hidden';

    var itemRect = item.getBoundingClientRect();
    var submenuWidth = submenu.getBoundingClientRect().width || 210;
    var viewportWidth = document.documentElement.clientWidth;
    var roomRight = viewportWidth - itemRect.right;
    var roomLeft = itemRect.left;

    item.classList.toggle(
      'tc4-submenu-open-left',
      roomRight < submenuWidth + 8 && roomLeft >= submenuWidth + 8
    );

    submenu.style.display = previousDisplay;
    submenu.style.visibility = previousVisibility;
  }

  function positionAll() {
    document.querySelectorAll(
      '#tc4-nav .dropdown-menu li.menu-item-has-children, ' +
      '#tc4-nav .dropdown-menu li.dropdown, ' +
      '#tc4-nav .sub-menu li.menu-item-has-children, ' +
      '#tc4-nav .sub-menu li.dropdown'
    ).forEach(positionSubmenu);
  }

  function initialiseNestedDropdowns() {
    var items = document.querySelectorAll(
      '#tc4-nav .dropdown-menu li.menu-item-has-children, ' +
      '#tc4-nav .dropdown-menu li.dropdown, ' +
      '#tc4-nav .sub-menu li.menu-item-has-children, ' +
      '#tc4-nav .sub-menu li.dropdown'
    );

    items.forEach(function (item) {
      item.addEventListener('mouseenter', function () {
        positionSubmenu(item);
      });
      item.addEventListener('focusin', function () {
        positionSubmenu(item);
      });
    });

    positionAll();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialiseNestedDropdowns);
  } else {
    initialiseNestedDropdowns();
  }

  window.addEventListener('resize', positionAll);
})();
