let cityButtonClick = false;

export const pagination = (e,name) => {
    const cardActive = document.querySelectorAll('.project-card-hidden');
    let  btnDepartamentItems = document.querySelectorAll('#filter-deparments .filter__accordion__wrapper--item input');
    let visibleButtons = [];

    if (cityButtonClick == false && name && name != "Todos") {
        document.querySelectorAll('#filter-deparments .filter__accordion__wrapper--item input').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.querySelectorAll('#filter-cities .filter__accordion__wrapper--item input').forEach(checkbox => {
            checkbox.checked = false;
        });
        const btnCitiesItems = document.querySelectorAll('#filter-cities .filter__accordion__wrapper--item input');
            btnCitiesItems.forEach((btn) => {
                const parentWrapper = btn.parentElement;
                    parentWrapper.style.display = 'none';
            });
        btnDepartamentItems.forEach((btn) => {
            cardActive.forEach((card) => {
                if (card.getAttribute('data-category') === name) {
                    if(btn.id === card.getAttribute('data-department')){
                        visibleButtons.push(btn.id);
                    }
                }
            });
        });
  
        btnDepartamentItems.forEach((btn) => {
            const parentWrapper = btn.parentElement;
            if (visibleButtons.includes(btn.id)) {
                parentWrapper.style.display = '';
            } else {
                parentWrapper.style.display = 'none';

            }
        });
    } else {
        cityButtonClick = false;
        if (name === "Todos") {
            btnDepartamentItems.forEach((btn) => {
                const parentWrapper = btn.parentElement;
                    parentWrapper.style.display = '';
            });
        }
    }
    let currentCategorySlug = document.getElementById('currentCategorySlug').dataset.slug;
    const container = document.querySelector('.pagination-container');
    const initialPage = 1;
    if (container) {
        if (!currentCategorySlug) {
            loadContent(initialPage, container, "Todos");
        } else {
            let Departments = getSelectedDepartments();
            let Cities = getSelectedCities();
            if (currentCategorySlug.trim() == "Todos los productos") {
                let path = window.location.pathname;
                let segments = path.split('/');
                let filteredSegments = segments.filter(function(segment) {
                    return segment !== '';
                });
                let lastPart = filteredSegments[filteredSegments.length - 1];
                currentCategorySlug = lastPart;
            }
            loadContent(initialPage, container, currentCategorySlug, Departments, Cities);
        }


    }


};
export const sortAZ = () => {
    setSortOrder('asc');
    let product_order = document.getElementById("product-order");
    let project_order = document.getElementById("order-project");
    if(product_order){
        product_order.innerText ="A - Z"
    }
    if(project_order){
        project_order.innerText ="A - Z"
    }
}

 export const sortZA = () => {
    setSortOrder('desc');
    let product_order = document.getElementById("product-order");
    let project_order = document.getElementById("order-project");
    if(product_order){
        product_order.innerText ="Z - A"
    }
    if(project_order){
        project_order.innerText ="Z - A"
    }
}

export const setSortOrder = (order) => {
    let container = document.querySelector('.pagination-container');
    let category = document.getElementById('currentCategorySlug').dataset.slug;
    loadContent(1, container, category,"","" , order)
}

document.addEventListener('DOMContentLoaded', function() {

    const container = document.querySelector('.pagination-container');
    if(container){
        if (container.id === "projects-container") {
            const btnCities = document.querySelectorAll('#filter-cities .filter__accordion__wrapper--item');
            btnCities.forEach((btn) => {
                btn.addEventListener('change', function() {
                    let currentCategorySlug = document.getElementById('currentCategorySlug').dataset.slug;
                    cityButtonClick = true;
                    const selectedDepartments = getSelectedDepartments();
                    const selectedCities = getSelectedCities();
                    loadContent(1, container, currentCategorySlug, selectedDepartments, selectedCities);
                    cityButtonClick = true;
                });
            });
    
    
            document.addEventListener("DOMContentLoaded", function() {
                attachChangeEvents();
            })
            
           
        }
    }

    document.body.addEventListener('click', function(e) {
        if (e.target.matches('.page-numbers')) {
            handlePageNumberClick(e);
        }
    });
    function handlePageNumberClick(e) {
        e.preventDefault();
        const page = e.target.getAttribute('data-page');
        let Departments = getSelectedDepartments();
        let Cities = getSelectedCities();
        let currentCategorySlug = document.getElementById('currentCategorySlug').dataset.slug;
        if (currentCategorySlug.trim() == "Todos los productos") {
            let path = window.location.pathname;
            let segments = path.split('/');
            let filteredSegments = segments.filter(function(segment) {
                return segment !== '';
            });
            let lastPart = filteredSegments[filteredSegments.length - 1];
            currentCategorySlug = lastPart;
        }

    const productOrderSpan = document.querySelector('#product-order');
    const projectOrderSpan = document.querySelector('#order-project');
    function getOrder(text) {
        if (text === 'Z - A') {
            return "Des";
        } else if (text === 'A - Z') {
            return "Asc";
        } else {
            return "";
        }
    }

    if (productOrderSpan) {
        const order = getOrder(productOrderSpan.innerText.trim());
        loadContent(page, container, currentCategorySlug, Departments, Cities, order);
    } 
    else if (projectOrderSpan) {
        const order = getOrder(projectOrderSpan.innerText.trim());
        loadContent(page, container, currentCategorySlug, Departments, Cities, order);
    } 
    else {
        loadContent(page, container, currentCategorySlug, Departments, Cities);
    }

        
        
    }
    const btnCleanFilters = document.querySelector('#btn-clean-filters');

    if (btnCleanFilters) {
        btnCleanFilters.addEventListener('click', () => {
            const container = document.querySelector('#projects-container');
            document.querySelectorAll('#filter-deparments .filter__accordion__wrapper--item input').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.querySelectorAll('#filter-cities .filter__accordion__wrapper--item input').forEach(checkbox => {
                checkbox.checked = false;
            });
            loadContent(1, container, currentCategorySlug, [], []);
            document.getElementById('filter-cities').style.display = "none";
            const todosElement = document.querySelector('.filter__accordion__wrapper .selector.item[data-category="Todos"]');
            todosElement.click();
        });
    }
    const btnDepartments = document.querySelectorAll('#filter-deparments .filter__accordion__wrapper--item input');
    btnDepartments.forEach((btn) => {
        btn.addEventListener('change', handleDepartmentChange);
    });
    document.querySelectorAll('.projects__order__button p').forEach(element => {
        element.addEventListener('click', (e) => {
            element.parentNode.parentNode.classList.toggle('active');
        })
    });

    var checkboxes = document.querySelectorAll('#filter-cities .filter__accordion__wrapper--item input');

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });

        });
    });
    var checkboxes2 = document.querySelectorAll('#filter-deparments .filter__accordion__wrapper--item input');

    checkboxes2.forEach(function(checkbox2) {
        checkbox2.addEventListener('change', function() {
            checkboxes2.forEach(function(cb) {
                if (cb !== checkbox2) {
                    cb.checked = false;
                }
            });

        });
    });
});
const getCurrentCategory = () => {
    const container = document.querySelector('#projects-container');
    if (container) {
        if (container.id === "projects-container") {
            const activeCategoryElement = document.querySelector('.filter__accordion__wrapper .selector.active');
            return activeCategoryElement ? activeCategoryElement.dataset.category : 'Todos';
        } else if (container.id === "products-container") {
            const currentCategorySlug = document.getElementById('currentCategorySlug').dataset.slug;
            return currentCategorySlug;
        } else {
            const activeCategoryElement = document.querySelector('.active .selector__text');
            return activeCategoryElement ? activeCategoryElement.innerHTML : 'Todos';
        }
    }
}

function uncheckCheckboxes(checkboxes) {
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

function handleDepartmentChange(e) {
    const citiesCheckboxes = document.querySelectorAll('#filter-cities input[type="checkbox"]');
    uncheckCheckboxes(citiesCheckboxes);
    let selectedDepartments = [];
    const container = document.querySelector('#projects-container');
    let selectedDepa = getSelectedDepartments();
    let selectedD = e.srcElement.id;

    selectedDepa.forEach(department => {
        if (department === selectedD) {
            selectedDepartments = selectedD;
        }
    });
    let selectedCities = getSelectedCities();
    const category = getCurrentCategory();
    const filterCities = document.getElementById('filter-cities');
    if (selectedDepartments.length > 0) {
        filterCities.style.display = "block";
    } else {
        filterCities.style.display = "none";
    }
    loadContent(1, container, category, selectedDepartments, selectedCities);
}

function loadContent(page, container, category, selectedDepartments, selectedCities, order) {
    const type = container.id;
    let categoryName;
    if (type === "installations-container") {
        categoryName = 'product-category';
    } else if (type === "news-container") {
        categoryName = 'news-category';
    } else if (type === "products-container") {
        categoryName = 'product-category';
    } else if (type === "projects-container") {
        categoryName = 'project-categorie';

    }

    if (categoryName === 'project-categorie' && (!selectedDepartments || selectedDepartments.length === 0) || categoryName != 'project-categorie') {
        axios.get(`/wp-json/your_namespace/v1/content?type=${type}&page=${page}&${category !== "Todos" ? `${categoryName}=${category}` : ''}&order=${order ? `${order}` : 'ASC'}`)
            .then(function(response) {
                container.innerHTML = response.data.html;
                document.querySelector('#pagination-container').innerHTML = response.data.pagination;
                if (type === 'products-container') {
                    document.querySelector('#total-products').innerHTML = response.data.total + " " + "Productos";
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    } else {
        axios.get(`/wp-json/your_namespace/v1/content?type=projects-container&$&page=${page}&${category !== "Todos" ? `project-categorie=${category}` : ''}&departament=${selectedDepartments}&city=${selectedCities}`)
            .then(function(response) {
                container.innerHTML = response.data.html;
                filterDepartments(category);
                document.querySelector('#pagination-container').innerHTML = response.data.pagination;
            })
            .catch(function(error) {
                console.log(error);
            });
    }


}



const filterDepartments = (category) => {
    const cardActive = document.querySelectorAll('.project-card-hidden');
    const btnCitiesItems = document.querySelectorAll('#filter-cities .filter__accordion__wrapper--item input');
    let visibleButtons = [];
    let  selectedDepartments = getSelectedDepartments();

    if (cityButtonClick == false) {
        visibleButtons = [];
        btnCitiesItems.forEach((btn) => {
            cardActive.forEach((card) => {
                if (card.getAttribute('data-department') === selectedDepartments[0] && (card.getAttribute('data-category') === category || category === "Todos" ) ) {
                    if(btn.id === card.getAttribute('data-city')){
                        visibleButtons.push(btn.id);
                    }
                }
            });
        });
  
        btnCitiesItems.forEach((btn) => {
            const parentWrapper = btn.parentElement;
            if (visibleButtons.includes(btn.id)) {
                parentWrapper.style.display = '';
            } else {
                parentWrapper.style.display = 'none';
            }
        });
    } else {
        cityButtonClick = false;
    }

};



const getSelectedDepartments = () => {
    let selectedDepartments = [];
    let checkedInputs = document.querySelectorAll('#filter-deparments .filter__accordion__wrapper--item input:checked');
    checkedInputs.forEach((input, index) => {
        if (input.checked === true) {
            selectedDepartments.push(input.id);
        }
    });
    return selectedDepartments
}

const getSelectedCities = () => {

    return Array.from(document.querySelectorAll('#filter-cities .filter__accordion__wrapper--item input:checked')).map(input => input.id);
}