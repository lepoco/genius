// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Linq;
using System.Windows;
using System.Windows.Controls;

namespace Genius.Views.Pages.Analysis
{
    /// <summary>
    /// Interaction logic for Workflow.xaml
    /// </summary>
    public partial class Workflow : Page
    {
        private Code.Engine.Predictor.Solver _currentSolver;
        private int _activeCondition;

        public Workflow()
        {
            InitializeComponent();
            this.InitializeWorkflow();
            this.LoadFirstCondition();
        }

        private void InitializeWorkflow()
        {
            this._currentSolver = new Code.Engine.Predictor.Solver();
            this._currentSolver.Build(Code.GH.CurrentExpertSystem);

            textQuestionText.Text = Code.GH.CurrentExpertSystem.Question;
        }

        private void LoadFirstCondition()
        {
            if (this._currentSolver.ActiveConditions.Count > 0)
            {
                this._activeCondition = this._currentSolver.ActiveConditions.First().Key;

                Genius.Code.Engine.Condition? firstCondition = Code.GH.CurrentExpertSystem.KnowledgeBase.GetConditionById(this._activeCondition);

                if (firstCondition != null)
                    textSearchedCondition.Text = firstCondition.Name;
            } 
        }

        private void AnsweredYes()
        {
            this._currentSolver.AddResponse(this._activeCondition, Code.Engine.Predictor.Response.Confirmed);
        }

        private void AnsweredNo()
        {
            this._currentSolver.AddResponse(this._activeCondition, Code.Engine.Predictor.Response.Negated);
        }

        private void AnsweredDunno()
        {
            this._currentSolver.AddResponse(this._activeCondition, Code.Engine.Predictor.Response.Indeterminate);
        }

        private void ActionButton_Click(object sender, RoutedEventArgs e)
        {
            string tag = (sender as MaterialWPF.Controls.MaterialButton).Tag.ToString();

            switch (tag)
            {
                case "yes":
                    this.AnsweredYes();
                    break;
                case "no":
                    this.AnsweredNo();
                    break;
                default:
                    this.AnsweredDunno();
                    break;
            }
        }
    }
}
